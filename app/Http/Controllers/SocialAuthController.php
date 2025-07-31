<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Add your user models
use App\Models\Jobseekers;
use App\Models\Trainers;
use App\Models\Recruiters;
use App\Models\Mentors;
use App\Models\Coach;
use App\Models\Assessors;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $role = $request->query('role'); // Get role from URL
        session(['social_role' => $role]); // Store for callback
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        $role = session('social_role');

        switch ($role) {
            case 'jobseeker':
                return $this->handleGoogleCallbackForJobseeker();
            case 'trainer':
                return $this->handleGoogleCallbackForTrainer();
            case 'recruiter':
                return $this->handleGoogleCallbackForRecruiter();
            case 'mentor':
                return $this->handleGoogleCallbackForMentor();
            case 'coach':
                return $this->handleGoogleCallbackForCoach();
            case 'assessor':
                return $this->handleGoogleCallbackForAssessor();
            default:
                session()->flash('error', 'Invalid role for social login.');
                return redirect()->route('home');
        }
    }


    private function handleSocialLogin($model, $guard, $redirectRoute, $registerRoute)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $model::where('email', $googleUser->getEmail())->first();
            $isNew = false;

            if (!$user) {
                $name = $googleUser->getName();
                $firstName = strtolower(trim(explode(' ', $name)[0]));
                $password = $firstName . '@talentrek';

                $user = $model::create([
                    'name' => $name,
                    'email' => $googleUser->getEmail(),
                    'status' => 'active',
                    'password' => Hash::make($password),
                    'pass' => $password,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                $isNew = true;
            }

            if ($user->status !== 'active') {
                session()->flash('error', 'Your account is inactive. Please contact administrator.');
                return redirect()->route($guard . '.sign-in');
            }

            Auth::guard($guard)->login($user);

            return $isNew
                ? redirect()->route($registerRoute)
                : redirect()->intended(route($redirectRoute));

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            session()->flash('error', 'Invalid state. Please try again.');
        } catch (\Exception $e) {
            session()->flash('error', 'Google login failed. Please try again.');
        }

        return redirect()->route($guard . '.sign-in');
    }


    public function handleGoogleCallbackForJobseeker()
    {
        return $this->handleSocialLogin(
            Jobseekers::class,
            'jobseeker',
            'jobseeker.profile',
            'jobseeker.registration'
        );
    }

    public function handleGoogleCallbackForTrainer()
    {
        return $this->handleSocialLogin(
            Trainers::class,
            'trainer',
            'trainer.dashboard',
            'trainer.registration'
        );
    }

    public function handleGoogleCallbackForRecruiter()
    {
        return $this->handleSocialLogin(
            Recruiters::class,
            'recruiter',
            'recruiter.dashboard',
            'recruiter.registration'
        );
    }

    public function handleGoogleCallbackForMentor()
    {
        return $this->handleSocialLogin(
            Mentors::class,
            'mentor',
            'mentor.dashboard',
            'mentor.registration'
        );
    }

    public function handleGoogleCallbackForCoach()
    {
        return $this->handleSocialLogin(
            Coach::class,
            'coach',
            'coach.dashboard',
            'coach.registration'
        );
    }

    public function handleGoogleCallbackForAssessor()
    {
        return $this->handleSocialLogin(
            Assessors::class,
            'assessor',
            'assessor.dashboard',
            'assessor.registration'
        );
    }
w


}

