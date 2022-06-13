import styled from "styled-components";
import {Button} from "react-bootstrap";

export const StyledForm = styled.form`
    margin-top: 50px;
    text-align: ${props => props.center === true ? 'center' : 'inherit'};
    .form-group {
        margin-bottom: 30px;
    }
`;

const QuizGuestUser = ({handleSubmit}) => {
    return (
        <StyledForm method='post' onSubmit={handleSubmit}>
            <div className='form-group'>
                <label>First Name</label>
                <input type='text' name='firstname' required={true} className='form-control' />
            </div>
            <div className='form-group'>
                <label>Last Name</label>
                <input type='text' name='lastname' required={true} className='form-control' />
            </div>
            <div className='form-group'>
                <label>Email</label>
                <input type='email' name='email' required={true} className='form-control' />
            </div>
            <div className='form-group'>
                <Button variant='success' type='submit'>Submit</Button>
            </div>
        </StyledForm>
    );
}

export default QuizGuestUser;
