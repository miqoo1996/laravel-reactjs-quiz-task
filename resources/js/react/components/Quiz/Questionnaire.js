import {useContext} from "react";
import {AppContext} from "../../context";
import {Button} from "react-bootstrap";
import {StyledQuestionWrapper, StyledCheckboxesWrapper, StyledHeader} from "./QuizStyled";
import {useQuestionnaire, useUserDetails} from "../../hooks/useQuiz";
import QuizGuestUser from "./QuizGuestUser";

const Questionnaire = ({mode, questions, tokenizationData, resetQuizData, endSessionDetails, endQuizSession, children}) => {
    const {MODE_BINARY, MODE_MULTIPLE} = useContext(AppContext);

    const {userAnswers, handleSubmitAnswer} = useQuestionnaire();

    const {userVerified, userDetails, handleSubmit} = useUserDetails();

    // expired or session ended
    if (tokenizationData.expired) {
        return (
            <>
                <StyledHeader center={true}>
                    <h3>Session Ended</h3>
                </StyledHeader>
                <StyledQuestionWrapper center={true}>
                    <p>Dear user, thank you for your participation!</p>
                    <p><strong>Your Score: <span style={{color: 'green'}}>{endSessionDetails.score}%</span></strong></p>
                    <p><strong>Answered Question: <span style={{color: 'green'}}>{endSessionDetails.answeredCount}</span></strong></p>
                    <p><strong>Unanswered Question: <span style={{color: 'green'}}>{endSessionDetails.unansweredCount}</span></strong></p>
                    <div><Button variant='success' onClick={e => resetQuizData()}>Take quiz again?</Button></div>
                </StyledQuestionWrapper>
            </>
        );
    }

    if (!userVerified) {
        return (
            <QuizGuestUser handleSubmit={handleSubmit} />
        );
    }

    return (
        <>
            <StyledQuestionWrapper>
                <ol>
                    {questions.map((question, index) => {
                        const userAnswer = userAnswers.find(a => a.question_id.toString() === question.id.toString());

                        if (userAnswer) {
                            return (
                                <li key={index}>
                                    <p>{question.title}</p>
                                    <hr />
                                    <div className='text-center'>
                                        {userAnswer.is_correct ? (
                                            <strong style={{color: 'blue'}}>Correct! The right answer is: <i>{userAnswer.correct?.title}</i></strong>
                                        ) : (
                                            <strong style={{color: 'red'}}>Sorry, you are wrong! The right answer is: <i>{userAnswer.correct?.title}</i></strong>
                                        )}
                                    </div>
                                </li>
                            );
                        }

                        return (
                            <li key={index}>
                                <p>{question.title}</p>
                                <hr />
                                <div>
                                    {question.answers.map((answer, key) => {
                                        return (
                                            <div key={key}>
                                                {mode === MODE_BINARY ? (
                                                    <StyledCheckboxesWrapper>
                                                        <input type="radio" name={'answer-' + index} value={answer.id} id={`answer-${index}-${key}`} />
                                                        <label htmlFor={`answer-${index}-${key}`}>{answer.title}</label>
                                                    </StyledCheckboxesWrapper>
                                                ) : null}

                                                {mode === MODE_MULTIPLE ? (
                                                    <StyledCheckboxesWrapper>
                                                        <input type="radio" name={'answer-' + index} value={answer.id} id={`answer-${index}-${key}`} />
                                                        <label htmlFor={`answer-${index}-${key}`}>{answer.title}</label>
                                                    </StyledCheckboxesWrapper>
                                                ) : null}
                                            </div>
                                        )
                                    })}

                                    <div className='clearfix'>
                                        <Button variant='success' style={{float: 'Right'}} onClick={e => handleSubmitAnswer(question, 'answer-' + index)}>Submit Answer</Button>
                                    </div>
                                </div>
                            </li>
                        );
                    })}
                </ol>
            </StyledQuestionWrapper>

            <div className='text-center'><Button variant="success" onClick={endQuizSession}>Submit Form</Button></div>

            {children}
        </>
    );
}

export default Questionnaire;
