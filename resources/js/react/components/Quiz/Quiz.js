import {useContext} from "react";
import {Button} from "react-bootstrap";
import Questionnaire from "./Questionnaire";
import {StyledFlexCenterWrapper, StyledHeader, StyledHeaderTimer, StyledQuizWrapper} from "./QuizStyled";
import {AppContext} from "../../context";
import useQuiz from "../../hooks/useQuiz";

const Quiz = () => {
    const {MODES} = useContext(AppContext);

    const {
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
    } = useQuiz();

    if (isLoading) {
        return (
            <StyledHeader center={true}>
                <h1>Please wait...</h1>
            </StyledHeader>
        );
    } else if (quizMotFound) {
        return (
            <StyledHeader center={true}>
                <h1>Quiz not found.</h1>
            </StyledHeader>
        );
    }

    return (
        <div>
            <StyledHeader center={true}>
                <h1>{quiz.title}</h1>

                <StyledHeaderTimer>
                    <CountDownTimer milliseconds={tokenizationData.duration} start={sessionStarted} onComplete={endQuizSession} />
                </StyledHeaderTimer>
            </StyledHeader>

            {tokenizationData.expired ? null : (
                <StyledHeader center={true}>
                    <StyledFlexCenterWrapper>
                        <div className='col-md-3' style={{margin: "30px auto"}}>
                            <label className='control-label'>Select Mode</label>
                            <select className='form-control' value={activeMode} onChange={e => handelChangeMode(e.currentTarget.value)}>
                                {modes.map(mode => {
                                    return (
                                        <option key={mode} value={mode}>{MODES[mode]}</option>
                                    );
                                })}
                            </select>
                        </div>
                    </StyledFlexCenterWrapper>
                </StyledHeader>
            )}

            {(sessionStarted && tokenizationData.exists) || tokenizationData.expired ? (
                <StyledQuizWrapper>
                    <Questionnaire
                        mode={data.activeMode}
                        questions={quiz.questions}
                        tokenizationData={tokenizationData}
                        resetQuizData={resetQuizData}
                        endSessionDetails={endSessionDetails}
                        endQuizSession={endQuizSession}>
                    </Questionnaire>
                </StyledQuizWrapper>
            ) : (
                <StyledQuizWrapper center={true}>
                    {!(!tokenizationData.expired && !quiz.questions.length) ? <Button variant="primary" onClick={e => tokenization()}>Start Session</Button> : null}

                    {!tokenizationData.expired && !quiz.questions.length ? (
                        <strong>Quiz has no question for the selected mode "<i>{data.activeMode}</i>" to start a new session.</strong>
                    ) : null}
                </StyledQuizWrapper>
            )}
        </div>
    );
};

export default Quiz;
