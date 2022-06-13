import React from 'react';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import Quizzes from "./components/Quiz/Quizzes";
import {AppContext, contextData} from "./context";
import Quiz from "./components/Quiz/Quiz";
import 'react-toastify/dist/ReactToastify.css';
import {ToastContainer} from "react-toastify";

const App = () => {
    return (
        <Router>
            <AppContext.Provider value={contextData}>
                <Routes>
                    <Route exact path="/" element={<Quizzes />} />
                    <Route exact path="/app/quiz/:id" element={<Quiz />} />
                    <Route path="*" element="Page not found." />
                </Routes>
                <ToastContainer />
            </AppContext.Provider>
        </Router>
    );
}

export default App;
