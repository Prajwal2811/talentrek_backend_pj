<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


// Admin Routes
Route::group(['prefix' => 'admin'], function() {
	Route::group(['middleware' => 'admin.guest'], function(){
		Route::view('/login', 'admin.login')->name('admin.login');
		Route::view('/forgot-password', 'admin.forgot-password')->name('admin.forgot-password');
		Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.auth');
		Route::post('/admin/send-reset-link', [App\Http\Controllers\AdminController::class, 'sendResetPassword'])->name('admin.send-reset-link');
	
	});
	
	Route::group(['middleware' => 'admin.auth'], function () {

		// Dashboard
		Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

		// Profile, Settings
		Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
		Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
		Route::post('/settings/store', [AdminController::class, 'settingsUpdate'])->name('admin.settings.store');
		Route::post('/settings/store-media', [AdminController::class, 'storeMediaLinks'])->name('admin.settings.store-media');

		// Logout
		Route::get('/logout', [AdminController::class, 'signOut'])->name('admin.signOut');

		// Admin Management (Superadmin only)
		Route::middleware('admin.module:Admin')->group(function () {
			Route::get('/admins/create', [AdminController::class, 'create'])->name('admin.create');
			Route::post('/admins/store', [AdminController::class, 'store'])->name('admin.store');
			Route::get('/admins', [AdminController::class, 'index'])->name('admin.index');
			Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
			Route::post('/admins/{id}', [AdminController::class, 'update'])->name('admin.update');
			Route::delete('/admins/{id}/delete', [AdminController::class, 'destroy'])->name('admin.destroy');
			Route::post('/admins/changeStatus', [AdminController::class, 'changeStatus'])->name('admin.changeStatus');
			Route::post('/admins/jobseekers/unassign', [AdminController::class, 'unassign'])->name('admin.jobseekers.unassign');
		});


		Route::middleware(['admin.module:User'])->group(function () {
			Route::get('/', [AdminController::class, 'users'])->name('admin.user.index');            // List users
			Route::get('/create', [AdminController::class, 'createUser'])->name('admin.user.create');    // Create user form
			Route::post('/store', [AdminController::class, 'storeUser'])->name('admin.user.store');      // Store new user
			Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('admin.user.edit');     // Edit user form
			Route::delete('/{id}/delete', [AdminController::class, 'destroyUser'])->name('admin.user.destroy'); // Delete user

			// Optional: status change or actions
			Route::post('/changeStatus', [AdminController::class, 'changeStatus'])->name('admin.user.changeStatus');
		});


		// Jobseekers Module
		Route::middleware('admin.module:Jobseekers')->group(function () {
			Route::get('/jobseekers', [AdminController::class, 'jobseekers'])->name('admin.jobseekers');
			Route::get('/jobseekers/{id}/view', [AdminController::class, 'jobseekerView'])->name('admin.jobseeker.view');
			Route::delete('/jobseekers/{id}/delete', [AdminController::class, 'jobseekerDestroy'])->name('admin.jobseeker.destroy');
			Route::post('/jobseekers/changeStatus', [AdminController::class, 'jobseekerChangeStatus'])->name('admin.jobseeker.changeStatus');
			Route::post('/jobseekers/assignAdmin', [AdminController::class, 'assignAdmin'])->name('admin.jobseeker.assignAdmin');
			Route::post('/jobseeker/update-status', [AdminController::class, 'updateStatus'])->name('admin.jobseeker.updateStatus');
		});



        // Jobseekers Module
		Route::middleware('admin.module:Trainers')->group(function () {
			Route::get('/trainers', [App\Http\Controllers\AdminController::class, 'trainers'])->name('admin.trainers');
            Route::get('/trainer/{id}/view', [App\Http\Controllers\AdminController::class, 'viewTrainer'])->name('admin.trainer.view');
            Route::get('/trainer/{id}/training-material', [App\Http\Controllers\AdminController::class, 'viewTrainingMaterial'])->name('admin.trainer.training-material');
            Route::delete('/trainer/{id}/delete', [App\Http\Controllers\AdminController::class, 'trainerDestroy'])->name('admin.trainer.destroy');
            Route::post('/trainer/changeStatus', [App\Http\Controllers\AdminController::class, 'trainerChangeStatus'])->name('admin.trainer.changeStatus');	
            Route::post('/trainer/update-status', [App\Http\Controllers\AdminController::class, 'updateStatusTrainer'])->name('admin.trainer.updateStatus');
            Route::delete('/trainer/{id}/delete', [App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.trainer.destroy');
            Route::get('/trainer/{trainer}/training-material/{material}/view', [App\Http\Controllers\AdminController::class, 'viewTrainingMaterialDetail'])->name('admin.trainer.training-material.view');


		});

        // Expats Module
        Route::middleware('admin.module:Expats')->group(function () {
			Route::get('/expat', [App\Http\Controllers\AdminController::class, 'expat'])->name('admin.expat');
		});

        
        // Assessors Module
        Route::middleware('admin.module:Assessors')->group(function () {
		    Route::get('/assessors', [App\Http\Controllers\AdminController::class, 'assessors'])->name('admin.assessors');
			
		});

        // Coach Module
        Route::middleware('admin.module:Coach')->group(function () {
		    Route::get('/coach', [App\Http\Controllers\AdminController::class, 'coach'])->name('admin.coach');
                    
        });

        // Mentor Module
        Route::middleware('admin.module:Mentor')->group(function () {
		    Route::get('/mentors', [App\Http\Controllers\AdminController::class, 'mentors'])->name('admin.mentors');
                    
        });

        // Payments
        Route::middleware('admin.module:Payments')->group(function () {
		    Route::get('/payments', [App\Http\Controllers\AdminController::class, 'payments'])->name('admin.payments');
                    
        });

        // Activity Log
        Route::middleware('admin.module:Log')->group(function () {
		    Route::get('/activity-log', [App\Http\Controllers\AdminController::class, 'showActivityLog'])->name('admin.activity.log');
        });

        // Subscriptions
        Route::middleware('admin.module:Subscriptions')->group(function () {
		    Route::get('/subscriptions', [App\Http\Controllers\AdminController::class, 'subscriptions'])->name('admin.subscriptions');
        });

        // Languages
        Route::middleware('admin.module:Languages')->group(function () {
		    Route::get('/languages', [App\Http\Controllers\AdminController::class, 'languages'])->name('admin.languages');
        });


        // Recruiters Module
        Route::middleware('admin.module:Recruiters')->group(function () {
			Route::get('/recruiters', [App\Http\Controllers\AdminController::class, 'recruiters'])->name('admin.recruiters');
            Route::get('/recruiter/{id}/view', [App\Http\Controllers\AdminController::class, 'recruiterView'])->name('admin.recruiter.view');
            Route::post('/recruiter/changeStatus', [App\Http\Controllers\AdminController::class, 'recruiterChangeStatus'])->name('admin.recruiter.changeStatus');	
            Route::post('/recruiter/update-status', [App\Http\Controllers\AdminController::class, 'updateRecruiterStatus'])->name('admin.recruiter.updateStatus');
		});

		// CMS Module
		Route::middleware('admin.module:CMS')->group(function () {
			Route::get('/cms', [AdminController::class, 'cms'])->name('admin.cms');
			Route::get('/cms/{slug}/edit', [AdminController::class, 'cmsEdit'])->name('admin.cms.edit');
			Route::post('/cms/banner-update', [AdminController::class, 'updateBanner'])->name('admin.cms.banner.update');
		});

		// Testimonials Module
		Route::middleware('admin.module:Testimonials')->group(function () {
			Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
			Route::get('/testimonials/add', [AdminController::class, 'createTestimonial'])->name('admin.testimonials.add');
			Route::post('/testimonials/add', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
			Route::get('/testimonials/manage', [AdminController::class, 'manageTestimonial'])->name('admin.testimonials.manage');
			Route::get('/testimonials/edit/{id}', [AdminController::class, 'editTestimonial'])->name('admin.testimonials.edit');
			Route::post('/testimonials/update/{id}', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
			Route::post('/testimonials/delete/{id}', [AdminController::class, 'destroyTestimonial'])->name('admin.testimonials.delete');
		});

	
		// Certification Template
		Route::middleware('admin.module:Certification Template')->group(function () {
			Route::get('/certification-template', [AdminController::class, 'certificationTemplate'])->name('admin.certification.template');
			Route::post('/certification-template-update', [AdminController::class, 'updateTemplate'])->name('admin.certification.update');
		});

	
	});
});
