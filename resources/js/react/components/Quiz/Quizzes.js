import styled from "styled-components";
import axios from "axios";
import {useEffect, useState, useContext} from "react";
import {AppContext} from "../../context";
import {Link} from "react-router-dom";

const StyledList = styled.ol`
    margin-top: 50px;
    li {
        margin-bottom: 20px;
    }
    a {
        color: #0a58ca;
        text-decoration: underline;
        margin-left: 10px;
    }
`;

const StyledHeader = styled.div`
    position: relative;
`;

const Quizzes = () => {
    const [quizzes, setQuizzes] = useState([]);

    const {apiUrl} = useContext(AppContext);

    useEffect(() => {
        axios.get(`${apiUrl}/quiz`).then(response => response.data).then(data => setQuizzes(data));
    }, [])

    return (
        <div>
            <StyledHeader>
                <h1 className="text-center">List of Quizzes</h1>
            </StyledHeader>

            <StyledList>
                {quizzes.map(quiz => {
                    return (
                        <li key={quiz.id}>
                            <Link to={{ pathname: `/app/quiz/${quiz.id}` }}>{quiz.title}</Link>
                        </li>
                    );
                })}
            </StyledList>
        </div>
    );
};

export default Quizzes;
