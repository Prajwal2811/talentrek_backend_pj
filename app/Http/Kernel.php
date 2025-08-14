<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
        'admin.guest' => \App\Http\Middleware\AdminRedirectIfAuthenticated::class,

        'jobseeker.auth' => \App\Http\Middleware\JobseekerAuthenticate::class,
        'jobseeker.guest' => \App\Http\Middleware\JobseekerRedirectIfAuthenticated::class,


        'recruiter.auth' => \App\Http\Middleware\RecruiterAuthenticate::class,
        'recruiter.guest' => \App\Http\Middleware\RecruiterRedirectIfAuthenticated::class,


        'trainer.auth' => \App\Http\Middleware\TrainerAuthenticate::class,
        'trainer.guest' => \App\Http\Middleware\TrainerRedirectIfAuthenticated::class,


        'assessor.auth' => \App\Http\Middleware\AssessorAuthenticate::class,
        'assessor.guest' => \App\Http\Middleware\AssessorRedirectIfAuthenticated::class,


        'coach.auth' => \App\Http\Middleware\CoachAuthenticate::class,
        'coach.guest' => \App\Http\Middleware\CoachRedirectIfAuthenticated::class,



        'mentor.auth' => \App\Http\Middleware\MentorAuthenticate::class,
        'mentor.guest' => \App\Http\Middleware\MentorRedirectIfAuthenticated::class,



        'auth.api' => \App\Http\Middleware\ApiAuthMiddleware::class,


        'admin.module' => \App\Http\Middleware\CheckAdminModulePermission::class,


        'check.trainer.subscription' => \App\Http\Middleware\CheckTrainerSubscription::class,
        'check.mentor.subscription' => \App\Http\Middleware\CheckMentorSubscription::class,
        'check.assessor.subscription' => \App\Http\Middleware\CheckAssessorSubscription::class,
        'check.coach.subscription' => \App\Http\Middleware\CheckCoachSubscription::class,
        'check.jobseeker.subscription' => \App\Http\Middleware\CheckJobseekerSubscription::class,
        'check.expat.subscription' => \App\Http\Middleware\CheckExpatSubscription::class,
        'check.recruiter.subscription' => \App\Http\Middleware\CheckRecruiterSubscription::class,


        
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        
    ];
}
