import {useEffect, useState, useContext, useCallback} from "react";
import {useParams} from "react-router";
import Countdown from "react-countdown";
import moment from "moment/moment";
import axios from "axios";
import {AppContext} from "../context";
import StringHelper from "../helpers/StringHelper";
import {toast} from "react-toastify";

const FormSerializeJSON = (form) => {
    // Create a new FormData object
    const formData = new FormData(form);

    // Create an object to hold the name/value pairs
    const pairs = {};

    // Add each name/value pair to the object
    for (const [name, value] of formData) {
        pairs[name] = value;
    }

    // Return the JSON string
    return pairs;
}

const updateUserToken = () => {
    if (!localStorage.getItem('user_token')) {
        localStorage.setItem('user_token', StringHelper.generateToken(60));
    }
}

updateUserToken();

const useQuiz = () => {
    const {apiUrl, MODE_BINARY} = useContext(AppContext);

    const { id } = useParams();

    const storageAnswers = localStorage.getItem('quiz_answers') ? JSON.parse(localStorage.getItem('quiz_answers')) : []

    const [userAnswers, setUserAnswers] = useState(storageAnswers);

    const [isLoading, setIsLoading] = useState(true);
    const [sessionStarted, setSessionStared] = useState(Boolean(localStorage.getItem('quiz_session_started')));
    const [data, setData] = useState([]);
    const [quiz, setQuiz] = useState(null);
    const [tokenizationData, setTokenizationData] = useState({duration: 0, expired: Boolean(localStorage.getItem('quiz_token_expired'))});
    const [modes, setModes] = useState([]);
    const [activeMode, setActiveMode] = useState(localStorage.getItem('activeMode') || '');
    const [quizMotFound, setQuizNotFound] = useState(true);
    const [endSessionDetails, setEndSessionDetails] = useState({
        answeredCount: 0,
        unansweredCount: 0,
        score: 0,
    });

    const endQuizSession = () => {
        if (localStorage.getItem('quiz_answers')) {
            const selectedAnswers = JSON.parse(localStorage.getItem('quiz_answers'));
            const corrects = selectedAnswers.filter(a => a.is_correct === true);
            setEndSessionDetails({
                answeredCount: selectedAnswers.length,
                unansweredCount: quiz.questions.length - selectedAnswers.length,
                score: Math.floor((corrects.length / quiz.questions.length) * 100),
            });
        } else {
            setEndSessionDetails({
                answeredCount: 0,
                unansweredCount: 0,
                score: 0,
            });
        }

        resetQuizData(false);
        localStorage.setItem('quiz_session_started', 0);
        localStorage.setItem('quiz_token_expired', 1);
        setTokenizationData(prevState => {
            const state = {
                ...prevState,
                duration: data.data.duration * 60 * 1000,
                exists: true,
                expired: true,
            };

            return {
                ...state
            };
        });
    };

    const tokenization = (startSession = true, callback = null, successCallback = null) => {
        axios.post(`${apiUrl}/quiz/session-token/${id}/${localStorage.getItem('user_token')}/tokenization`, startSession ? {
            make: true,
            activeMode: activeMode || MODE_BINARY,
            user_token: localStorage.getItem('user_token'),
        } : {
            token: localStorage.getItem('quiz_token'),
            activeMode: activeMode || MODE_BINARY,
            user_token: localStorage.getItem('user_token')
        }).then(response => response.data).then(data => {
            if (callback) {
                return callback(data);
            }

            setTokenizationData(data);
            setSessionStared(startSession);
            localStorage.setItem('quiz_token_expired', data.expired ? 1 : 0);
            localStorage.setItem('quiz_session_started', startSession ? 1 : 0);
            localStorage.setItem('quiz_token', data.token);

            if (successCallback) {
                return successCallback(data);
            }
        });
    };

    const initialize = (openSession = false) => {
        const prefix = activeMode ? '/' + activeMode : '';

        axios.get(`${apiUrl}/quiz/${id}${prefix}`).then(response => response.data).then(data => {
            setData(data);
            setModes(data.modes);
            setQuiz(data.data);
            setQuizNotFound(false);

            if (sessionStarted) {
                if (openSession) {
                    tokenization(true, null, () => setIsLoading(false));
                } else {
                    tokenization(false, data => {
                        setTokenizationData(data);
                        setIsLoading(false);
                    });
                }
            } else {
                setTokenizationData({duration: data.data.duration * 60 * 1000, expired: Boolean(localStorage.getItem('quiz_token_expired'))});
                setIsLoading(false);
            }
        }).catch(e => {
            setIsLoading(false);
        });
    };

    const handelChangeMode = (mode) => {
        if (sessionStarted && confirm(data.restart_session_text)) {
            localStorage.removeItem('quiz_answers');
            setUserAnswers([]);
            tokenization();
        }

        localStorage.setItem('activeMode', mode);

        setActiveMode(mode);
    }

    const resetQuizData = (updateTokenizationData = true) => {
        localStorage.removeItem('user_token');
        localStorage.removeItem('quiz_token');
        localStorage.removeItem('quiz_session_started');
        localStorage.removeItem('quiz_token_expired');
        localStorage.removeItem('quiz_answers');
        setSessionStared(false);
        if (updateTokenizationData) {
            setTokenizationData(prevState => {
                const state = {
                    ...prevState,
                    expired: false,
                    exists: false,
                };
                return {...state};
            })
        }
        updateUserToken();
    };

    const CountDownTimer = useCallback(({start, milliseconds, onComplete}) => {
        return <Countdown autoStart={start === true} date={moment() + milliseconds} onComplete={onComplete} />;
    }, [quiz, sessionStarted, tokenizationData]);

    useEffect(() => {
        initialize();
    }, [activeMode]);

    return {
        isLoading,
        quizMotFound,
        tokenizationData,
        CountDownTimer,
        resetQuizData,
        sessionStarted,
        handelChangeMode,
        endQuizSession,
        tokenization,
        activeMode,
        data,
        modes,
        quiz,
        endSessionDetails
    };
}

const useQuestionnaire = () => {
    const {apiUrl} = useContext(AppContext);

    const [userAnswers, setUserAnswers] = useState([]);

    useEffect(() => {
        setUserAnswers( localStorage.getItem('quiz_answers') ? JSON.parse(localStorage.getItem('quiz_answers')) : []);
    }, [localStorage.getItem('quiz_answers')]);

    const handleSubmitAnswer = (question, name) => {
        const value = document.querySelector(`[name="${name}"]:checked`)?.value;

        if (value) {
            axios.put(`${apiUrl}/quiz/${question.quiz_id}/${localStorage.getItem('user_token')}/submit-answer`, {
                question_id: question.id,
                answer_id: value,
            }).then(response => response.data).then(data => {
                setUserAnswers((prevState) => {
                    const state = prevState;

                    state.push({
                        question_id: question.id,
                        answer_id: value,
                        ...data,
                    });

                    localStorage.setItem('quiz_answers', JSON.stringify(state));

                    return [...state];
                });
            });
        } else {
            toast.error('Please select an answer')
        }
    };

    return {
        userAnswers,
        handleSubmitAnswer
    };
}

const useUserDetails = () => {
    const {apiUrl} = useContext(AppContext);

    const { id } = useParams();

    const [userVerified, setUserVerified] = useState(false);
    const [userDetails, setUserDetails] = useState({});

    const handleSubmit = (e) => {
        e.preventDefault();

        axios.put(`${apiUrl}/quiz/${id}/${localStorage.getItem('user_token')}/add-guest-user`, FormSerializeJSON(e.currentTarget)).then(response => response.data).then(data => {
            if (Object.values(data).length) {
                setUserVerified(true);
                setUserDetails(data);
            }
        }).catch(error => {
            const data = error.response.data;
            if (typeof data.errors === 'object') {
                const firstErrors = Object.values(data.errors)[0];
                toast.error(firstErrors[0]);
            }
        });
    }

    const initializeUserDetails = () => {
        setUserVerified(false);
        setUserDetails({ });
    };

    const updateUserDetails = () => {
        axios.get(`${apiUrl}/quiz/${id}/${localStorage.getItem('user_token')}/get-guest-user`).then(response => response.data).then(data => {
            if (Object.values(data).length) {
                setUserVerified(true);
                setUserDetails(data);
            }
        });
    };

    useEffect(() => {
        updateUserDetails()
    }, [userVerified, localStorage.getItem('user_token')]);

    useEffect(() => {
        const answers = localStorage.getItem('quiz_answers') ? JSON.parse(localStorage.getItem('quiz_answers')) : [];

        if (!answers.length) {
            initializeUserDetails();
        }

    }, [localStorage.getItem('quiz_answers')])

    return {
        userVerified: userVerified,
        userDetails: {
            ...userDetails,
            token: localStorage.getItem('user_token'),
        },
        handleSubmit,
        updateUserDetails,
        initializeUserDetails
    };
}

export {
    useQuestionnaire,
    useUserDetails,
};

export default useQuiz;
