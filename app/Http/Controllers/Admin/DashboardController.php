<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Quiz\UserQuizService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private UserQuizService $userQuizService;

    public function __construct(UserQuizService $userQuizService)
    {
        $this->userQuizService = $userQuizService;
    }

    public function index() : View
    {
        $statistics = $this->userQuizService->getQuizStatistics();

        return view('admin.dashboard.index', compact('statistics'));
    }
}
