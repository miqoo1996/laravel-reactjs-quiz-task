<?php

namespace App\Services\Quiz;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\QuizAnswer;
use App\Models\Quiz\QuizQuestion;
use App\Repositories\Quiz\QuizAnswerRepository;
use App\Repositories\Quiz\QuizQuestionRepository;
use App\Repositories\Quiz\QuizRepository;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Model;

class QuizService extends AbstractService
{
    protected QuizRepository $quizRepository;
    protected QuizQuestionRepository $quizQuestionRepository;
    protected QuizAnswerRepository $quizAnswerRepository;

    protected ?string $quizMode = null;

    public function __construct(
        QuizRepository $quizRepository,
        QuizQuestionRepository $quizQuestionRepository,
        QuizAnswerRepository $quizAnswerRepository
    )
    {
        $this->quizRepository = $quizRepository;
        $this->quizQuestionRepository = $quizQuestionRepository;
        $this->quizAnswerRepository = $quizAnswerRepository;
    }

    public function getQuizRepository(): QuizRepository
    {
        return $this->quizRepository;
    }

    public function getQuizQuestionRepository(): QuizQuestionRepository
    {
        return $this->quizQuestionRepository;
    }

    public function getQuizAnswerRepository(): QuizAnswerRepository
    {
        return $this->quizAnswerRepository;
    }

    public function setQuizMode(?string $quizMode, bool $applyQuery = true, array $questionRelations = []): self
    {
        $this->quizMode = $quizMode;

        if ($applyQuery) {
            $this->quizRepository->applyQuestionMode($quizMode, $questionRelations);
        }

        return $this;
    }

    public function getListQuizzes(bool $paginated = true, $ordered = false, array $relations = [])
    {
        if ($ordered) {
            $this->quizRepository->applyDefaultOrder();
        }

        if ($relations) {
            $this->quizQuestionRepository->applyRelations($relations);
        }

        return $this->quizRepository->fetchAll($paginated);
    }

    public function getListQuizQuestions($quizId = null, bool $paginated = true, $ordered = false, array $relations = [])
    {
        if ($quizId) {
            $this->quizQuestionRepository->applyQuizId($quizId);
        }

        if ($ordered) {
            $this->quizQuestionRepository->applyDefaultOrder();
        }

        if ($relations) {
            $this->quizQuestionRepository->applyRelations($relations);
        }

        return $this->quizQuestionRepository->fetchAll($paginated);
    }

    public function getListQuizAnswers($questionId = null, bool $paginated = true, $ordered = false, array $relations = [])
    {
        if ($questionId) {
            $this->quizAnswerRepository->applyQuizQuestionId($questionId);
        }

        if ($ordered) {
            $this->quizAnswerRepository->applyDefaultOrder();
        }

        if ($relations) {
            $this->quizAnswerRepository->applyRelations($relations);
        }

        return $this->quizAnswerRepository->fetchAll($paginated);
    }

    /**
     * @return Quiz|Model|null
     */
    public function fetchQuizById($id, bool $failOnFind = false, bool $reload = false, array $relations = []) :? Quiz
    {
        if (!empty($relations)) {
            $this->quizRepository->applyRelations($relations);
        }

        return $this->fetchById($this->quizRepository, $id, $failOnFind, $reload);
    }

    /**
     * @return QuizQuestion|Model|null
     */
    public function fetchQuizQuestionById($id, bool $failOnFind = false, bool $reload = false) :? QuizQuestion
    {
        return $this->fetchById($this->quizQuestionRepository, $id, $failOnFind, $reload);
    }

    /**
     * @return QuizAnswer|Model|null
     */
    public function fetchQuizAnswerById($id, bool $failOnFind = false, bool $reload = false) :? QuizAnswer
    {
        return $this->fetchById($this->quizAnswerRepository, $id, $failOnFind, $reload);
    }

    public function deleteQuizByConditions(array $conditions = [], bool $returnDeleted = false, bool $failOnFind = true) :? int
    {
        $this->quizRepository->applyWhereConditions($conditions);

        return $this->quizRepository->delete($returnDeleted, $failOnFind);
    }

    public function deleteQuizQuestionByConditions(array $conditions = [], bool $returnDeleted = false, bool $failOnFind = true)
    {
        $this->quizQuestionRepository->applyWhereConditions($conditions);

        return $this->quizQuestionRepository->delete($returnDeleted, $failOnFind);
    }

    public function deleteQuizAnswerByConditions(array $conditions = [], bool $returnDeleted = false, bool $failOnFind = true)
    {
        $this->quizAnswerRepository->applyWhereConditions($conditions);

        return $this->quizAnswerRepository->delete($returnDeleted, $failOnFind);
    }
}
