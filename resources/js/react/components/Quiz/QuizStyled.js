import styled from "styled-components";

export const StyledQuizWrapper = styled.div`
    margin-top: 50px;
    text-align: ${props => props.center === true ? 'center' : 'inherit'};
`;

export const StyledHeader = styled.div`
    position: relative;
    text-align: ${props => props.center === true ? 'center' : 'inherit'};
`;

export const StyledFlexCenterWrapper = styled.div`
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
`;

export const StyledHeaderTimer = styled.div`
     position: absolute;
     top: -20px;
     right: -10px;
     font-weight: bold;
     font-style: italic;
`;

export const StyledQuestionWrapper = styled.div`
    text-align: ${props => props.center === true ? 'center' : 'inherit'};
    margin-bottom: 60px;
    li {
        padding: 30px;
        box-shadow: 1px 3px 12px -5px;
        border-radius: 20px;
        margin-bottom: 30px;
    }
`;

export const StyledCheckboxesWrapper = styled.div`
    display:flex;
    align-items: center;
    column-gap: 10px;
`;
