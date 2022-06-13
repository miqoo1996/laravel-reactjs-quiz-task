<?php

namespace App\Http\Requests\Quiz;

use App\Models\Quiz\QuizQuestion;
use Illuminate\Foundation\Http\FormRequest;

class ProcessQuizQuestionRequest extends FormRequest
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
            'quiz_id' => $this->quizId,
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
            'quiz_id' => 'required|exists:quizzes,id',
            'title' => 'required|string|max:255',
            'mode' => 'required|in:' . implode(',', QuizQuestion::MODES),
        ];
    }
}
