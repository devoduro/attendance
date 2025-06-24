<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamTemplate;
use App\Policies\ExamPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\ExamTemplatePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Exam::class => ExamPolicy::class,
        Question::class => QuestionPolicy::class,
        ExamTemplate::class => ExamTemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for specific actions if needed
        Gate::define('manage-exams', function ($user) {
            return $user->hasRole('teacher') || $user->hasRole('admin');
        });

        Gate::define('manage-questions', function ($user) {
            return $user->hasRole('teacher') || $user->hasRole('admin');
        });

        Gate::define('manage-exam-templates', function ($user) {
            return $user->hasRole('teacher') || $user->hasRole('admin');
        });

        Gate::define('view-exam-analytics', function ($user) {
            return $user->hasRole('teacher') || $user->hasRole('admin');
        });
    }
}
