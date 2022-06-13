<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuizSubmitAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question_id' => 'required|exists:quiz_questions,id,quiz_id,' . $this->route('id'),
            'answer_id' => 'required|exists:quiz_answers,id,quiz_question_id,' . $this->get('question_id'),
        ];
    }
}
