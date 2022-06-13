<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class ProcessQuizAnswerRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'quiz_question_id' => $this->quizQuestionId,
            'is_correct' => $this->is_correct ?: false,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quiz_question_id' => 'required|exists:quiz_questions,id',
            'title' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
        ];
    }
}
