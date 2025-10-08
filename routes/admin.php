<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;

// ==================== Admin Routes ====================
Route::group(['prefix' => 'admin'], function() {

    // ------------------ Guest Routes ------------------
    // Routes accessible only to non-authenticated admins (login, forgot password)
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::view('/login', 'admin.login')->name('admin.login'); // Admin login page
        Route::view('/forgot-password', 'admin.forgot-password')->name('admin.forgot-password'); // Forgot password page
        Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.auth'); // Process login
        Route::post('/admin/send-reset-link', [AdminController::class, 'sendResetPassword'])->name('admin.send-reset-link'); // Send password reset link
    });
    
    // ------------------ Authenticated Admin Routes ------------------
    // Routes accessible only to logged-in admins
    Route::group(['middleware' => 'admin.auth'], function () {
        
        // Dashboard & Profile
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard'); // Admin dashboard
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile'); // Admin profile page
        Route::post('/admin/profile/update/{id}', [AdminController::class, 'updateAdminProfile'])->name('admin.update.profile'); // Update profile
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings'); // Admin settings page
        Route::post('/settings/store', [AdminController::class, 'settingsUpdate'])->name('admin.settings.store'); // Update settings
        Route::post('/settings/store-media', [AdminController::class, 'storeMediaLinks'])->name('admin.settings.store-media'); // Store media links
        Route::get('/logout', [AdminController::class, 'signOut'])->name('admin.signOut'); // Admin logout

        // ------------------ Admin Management ------------------
        // Only accessible by Superadmin (role-based middleware)
        Route::middleware('admin.module:Admin')->group(function () {
            Route::get('/admins/create', [AdminController::class, 'create'])->name('admin.create'); // Form to create admin
            Route::post('/admins/store', [AdminController::class, 'store'])->name('admin.store'); // Store new admin
            Route::get('/admins', [AdminController::class, 'index'])->name('admin.index'); // List all admins
            Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit'); // Edit admin form
            Route::post('/admins/{id}', [AdminController::class, 'update'])->name('admin.update'); // Update admin
            Route::delete('/admins/{id}/delete', [AdminController::class, 'destroy'])->name('admin.destroy'); // Delete admin
            Route::post('/admins/changeStatus', [AdminController::class, 'changeStatus'])->name('admin.changeStatus'); // Change admin status
            Route::post('/admins/jobseekers/unassign', [AdminController::class, 'unassign'])->name('admin.jobseekers.unassign'); // Unassign jobseekers from admin
        });

        // ------------------ User Module ------------------
        Route::middleware(['admin.module:User'])->group(function () {
            Route::get('/', [AdminController::class, 'users'])->name('admin.user.index'); // List users
            Route::get('/create', [AdminController::class, 'createUser'])->name('admin.user.create'); // Form to create user
            Route::post('/store', [AdminController::class, 'storeUser'])->name('admin.user.store'); // Store new user
            Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('admin.user.edit'); // Edit user form
            Route::delete('/{id}/delete', [AdminController::class, 'destroyUser'])->name('admin.user.destroy'); // Delete user
            Route::post('/changeStatus', [AdminController::class, 'changeStatus'])->name('admin.user.changeStatus'); // Change user status
        });

        // ------------------ Jobseekers Module ------------------
        Route::middleware('admin.module:Jobseekers')->group(function () {
            Route::get('/jobseekers', [AdminController::class, 'jobseekers'])->name('admin.jobseekers'); // List jobseekers
            Route::get('/jobseekers/{id}/view', [AdminController::class, 'jobseekerView'])->name('admin.jobseeker.view'); // View jobseeker details
            Route::delete('/jobseekers/{id}/delete', [AdminController::class, 'jobseekerDestroy'])->name('admin.jobseeker.destroy'); // Delete jobseeker
            Route::post('/jobseekers/changeStatus', [AdminController::class, 'jobseekerChangeStatus'])->name('admin.jobseeker.changeStatus'); // Change jobseeker status
            Route::post('/jobseekers/assignAdmin', [AdminController::class, 'assignAdmin'])->name('admin.jobseeker.assignAdmin'); // Assign admin to jobseeker
            Route::post('/jobseeker/update-status', [AdminController::class, 'updateStatus'])->name('admin.jobseeker.updateStatus'); // Update jobseeker status

            // Resume & Certificate downloads
            Route::get('/jobseeker/{id}/resume/download', [AdminController::class, 'downloadResume'])->name('admin.jobseeker.resume.download'); // Download resume
            Route::get('admin/jobseeker/{jobseeker_id}/certificate/{material_id}',[AdminController::class, 'viewCertificate'])->name('admin.viewCertificate'); // View certificate
        });

        // ------------------ Expat Module ------------------
        Route::middleware('admin.module:Expats')->group(function () {
            Route::get('/expat', [AdminController::class, 'expat'])->name('admin.expat'); // List expats
            Route::get('/expat/{id}/view', [AdminController::class, 'expatView'])->name('admin.expat.view'); // View expat
            Route::delete('/expat/{id}/delete', [AdminController::class, 'expatDestroy'])->name('admin.expat.destroy'); // Delete expat
            Route::post('/expat/changeStatus', [AdminController::class, 'expatChangeStatus'])->name('admin.expat.changeStatus'); // Change expat status
            Route::post('/expat/assignAdmin', [AdminController::class, 'assignAdmin'])->name('admin.expat.assignAdmin'); // Assign admin
            Route::post('/expat/update-status', [AdminController::class, 'updateStatus'])->name('admin.expat.updateStatus'); // Update expat status
        });

        // ------------------ Trainers Module ------------------
        Route::middleware('admin.module:Trainers')->group(function () {
            Route::get('/trainers', [AdminController::class, 'trainers'])->name('admin.trainers'); // List trainers
            Route::get('/trainer/{id}/view', [AdminController::class, 'viewTrainer'])->name('admin.trainer.view'); // View trainer details
            Route::get('/trainer/{id}/training-material', [AdminController::class, 'viewTrainingMaterial'])->name('admin.trainer.training-material'); // View trainer training materials
            Route::delete('/trainer/{id}/delete', [AdminController::class, 'trainerDestroy'])->name('admin.trainer.destroy'); // Delete trainer
            Route::post('/trainer/changeStatus', [AdminController::class, 'trainerChangeStatus'])->name('admin.trainer.changeStatus'); // Change trainer status
            Route::post('/trainer/update-status', [AdminController::class, 'updateStatusTrainer'])->name('admin.trainer.updateStatus'); // Update trainer status
            Route::get('/trainer/{trainer}/training-material/{material}/view', [AdminController::class, 'viewTrainingMaterialDetail'])->name('admin.trainer.training-material.view'); // View specific material
            Route::post('/trainer/material/updateStatus', [AdminController::class, 'trainingMaterialChangeStatus'])->name('admin.trainer.material.updateStatus'); // Update material status
            Route::get('/trainer/{id}/training-assessment', [AdminController::class, 'viewTrainerAssessment'])->name('admin.trainer.training-assessment'); // View trainer assessments
            Route::get('/trainer/{trainer}/training-assessment/{assessment}/view', [AdminController::class, 'viewTrainingAssessmentDetail'])->name('admin.trainer.training-assessment.view'); // View specific assessment
        });

        // ------------------ Assessors Module ------------------
        Route::middleware('admin.module:Assessors')->group(function () {
            Route::get('/assessors', [AdminController::class, 'assessors'])->name('admin.assessors'); // List assessors
            Route::post('/assessor/changeStatus', [AdminController::class, 'assessorChangeStatus'])->name('admin.assessor.changeStatus'); // Change status
            Route::get('/assessor/{id}/view', [AdminController::class, 'viewassessor'])->name('admin.assessor.view'); // View assessor
            Route::get('/assessor/{id}/booking-session', [AdminController::class, 'viewassessorBookingSession'])->name('admin.assessor.booking-session'); // View assessor bookings
            Route::post('/assessor/update-status', [AdminController::class, 'updateassessorStatus'])->name('admin.assessor.updateStatus'); // Update status
        });

        // ------------------ Coach Module ------------------
        Route::middleware('admin.module:Coach')->group(function () {
            Route::get('/coach', [AdminController::class, 'coach'])->name('admin.coach'); // List coaches
            Route::post('/coach/changeStatus', [AdminController::class, 'coachChangeStatus'])->name('admin.coach.changeStatus'); // Change status
            Route::get('/coach/{id}/view', [AdminController::class, 'viewCoach'])->name('admin.coach.view'); // View coach
            Route::get('/coach/{id}/booking-session', [AdminController::class, 'viewCoachBookingSession'])->name('admin.coach.booking-session'); // View bookings
            Route::post('/coach/update-status', [AdminController::class, 'updateCoachStatus'])->name('admin.coach.updateStatus'); // Update status
        });

        // ------------------ Mentor Module ------------------
        Route::middleware('admin.module:Mentors')->group(function () {
            Route::get('/mentor', [AdminController::class, 'mentors'])->name('admin.mentor'); // List mentors
            Route::get('/mentor/{id}/view', [AdminController::class, 'viewMentor'])->name('admin.mentor.view'); // View mentor
            Route::get('/mentor/{id}/booking-session', [AdminController::class, 'viewBookingSession'])->name('admin.mentor.booking-session'); // View bookings
            Route::post('/mentor/changeStatus', [AdminController::class, 'mentorChangeStatus'])->name('admin.mentor.changeStatus'); // Change status
            Route::post('/mentor/update-status', [AdminController::class, 'updateMentorStatus'])->name('admin.mentor.updateStatus'); // Update status
        });

        // ------------------ Payments ------------------
        Route::middleware('admin.module:Payments')->group(function () {
            Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments'); // List payments
            Route::get('/payment/{id}/view', [AdminController::class, 'viewPayment'])->name('admin.payment.view'); // View payment details
        });

        // ------------------ Activity Log ------------------
        Route::middleware('admin.module:Log')->group(function () {
            Route::get('/activity-log', [AdminController::class, 'showActivityLog'])->name('admin.activity.log'); // View activity log
        });

        // ------------------ Subscriptions ------------------
        Route::middleware('admin.module:Subscriptions')->group(function () {
            Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('admin.subscriptions'); // List subscriptions
            Route::get('/subscription-plans/{type}', [AdminController::class, 'showSubscriptions'])->name('admin.subscription.subscription-plans.view'); // View plans by type
            Route::post('/subscriptions-store', [AdminController::class, 'subscriptionsStore'])->name('admin.subscription.store'); // Store subscription
        });

        // ------------------ Languages ------------------
        Route::middleware('admin.module:Languages')->group(function () {
            Route::get('/languages', [AdminController::class, 'languages'])->name('admin.languages'); // List languages
            Route::post('/language/update', [AdminController::class, 'updateLanguage'])->name('admin.language.update'); // Update language
        });

        // ------------------ Recruiters Module ------------------
        Route::middleware('admin.module:Recruiters')->group(function () {
            Route::get('/recruiters', [AdminController::class, 'recruiters'])->name('admin.recruiters'); // List recruiters
            Route::get('/recruiter/{id}/view', [AdminController::class, 'recruiterView'])->name('admin.recruiter.view'); // View recruiter
            Route::get('/recruiter/{id}/shortlisted-jobseekers', [AdminController::class, 'viewShortlistedJobseekers'])->name('admin.recruiter.shortlisted-jobseekers'); // View shortlisted jobseekers
            Route::post('/shortlist/update-status', [AdminController::class, 'updateStatusForShortlist'])->name('admin.shortlist.updateStatus'); // Update shortlist status
            Route::post('/recruiter/changeStatus', [AdminController::class, 'recruiterChangeStatus'])->name('admin.recruiter.changeStatus'); // Change recruiter status
            Route::post('/recruiter/update-status', [AdminController::class, 'updateRecruiterStatus'])->name('admin.recruiter.updateStatus'); // Update recruiter status
        });

        // ------------------ CMS Module ------------------
        Route::middleware('admin.module:CMS')->group(function () {
            Route::get('/cms', [AdminController::class, 'cms'])->name('admin.cms'); // List CMS pages
            Route::get('/cms/{slug}/edit', [AdminController::class, 'cmsEdit'])->name('admin.cms.edit'); // Edit CMS page
            Route::post('/cms/banner-update', [AdminController::class, 'updateBanner'])->name('admin.cms.banner.update'); // Update banner
        });

        // ------------------ Training Category ------------------
        Route::middleware('admin.module:Training Category')->group(function () {
            Route::get('/training-category/add', [AdminController::class, 'createCategory'])->name('admin.trainingcategory.add'); // Add training category
            Route::post('/training-category/add', [AdminController::class, 'storeCategory'])->name('admin.trainingcategory.store'); // Store category
            Route::get('/training-category', [AdminController::class, 'trainingCategory'])->name('admin.training-category'); // List categories
            Route::get('/training-category/{id}/edit', [AdminController::class, 'trainingCategoryEdit'])->name('admin.trainingcategory.edit'); // Edit category
            Route::post('/training-category/training-category-update/{id}', [AdminController::class, 'updatetrainingCategory'])->name('admin.trainingcategory.update'); // Update category
            Route::delete('/training-category/{id}/delete', [AdminController::class, 'trainingCategoryDestroy'])->name('admin.trainingcategory.delete'); // Delete category
        });

        // ------------------ Testimonials ------------------
        Route::middleware('admin.module:Testimonials')->group(function () {
            Route::get('/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials'); // List testimonials
            Route::get('/testimonials/add', [AdminController::class, 'createTestimonial'])->name('admin.testimonials.add'); // Add testimonial
            Route::post('/testimonials/add', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store'); // Store testimonial
            Route::get('/testimonials/manage', [AdminController::class, 'manageTestimonial'])->name('admin.testimonials.manage'); // Manage testimonials
            Route::get('/testimonials/edit/{id}', [AdminController::class, 'editTestimonial'])->name('admin.testimonials.edit'); // Edit testimonial
            Route::post('/testimonials/update/{id}', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update'); // Update testimonial
            Route::post('/testimonials/delete/{id}', [AdminController::class, 'destroyTestimonial'])->name('admin.testimonials.delete'); // Delete testimonial
        });

        // ------------------ Reviews ------------------
        Route::middleware('admin.module:Reviews')->group(function () {
            Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews'); // List reviews
            Route::get('/reviews/{id}/view', [AdminController::class, 'viewReview'])->name('admin.reviews.view'); // View review
        });

        // ------------------ Certification Template ------------------
        Route::middleware('admin.module:Certification Template')->group(function () {
            Route::get('/certification-template', [AdminController::class, 'certificationTemplate'])->name('admin.certification.template'); // Certification template
            Route::post('/certification-template-update', [AdminController::class, 'updateTemplate'])->name('admin.certification.update'); // Update template
        });

        // ------------------ Contact Support ------------------
        Route::middleware('admin.module:Contact Support')->group(function () {
            Route::get('/contact-support', [AdminController::class, 'contactSupport'])->name('admin.contact_support'); // Contact support page
        });

        // ------------------ Resume Format ------------------
        Route::middleware('admin.module:Resume Format')->group(function () {
            Route::get('/resume-format', [AdminController::class, 'resume'])->name('admin.resume'); // Resume format page
            Route::post('/resume-format/store', [AdminController::class, 'resumeUpdate'])->name('admin.resume.store'); // Update resume
            Route::get('/resume/download-option/{id}', [AdminController::class, 'downloadOption'])->name('admin.resume.download.option'); // Resume download option
        });

        // ------------------ Taxation ------------------
        Route::middleware('admin.module:Taxation')->group(function () {
            Route::get('/taxations', [AdminController::class, 'taxations'])->name('admin.taxations.taxations'); // List taxations
            Route::get('/taxations/create', [AdminController::class, 'createTax'])->name('admin.taxations.create'); // Create tax
            Route::post('/taxations', [AdminController::class, 'storeTax'])->name('admin.taxations.store'); // Store tax
            Route::get('/taxations/{type}', [AdminController::class, 'showTaxations'])->name('admin.taxations.taxations.view'); // Show taxations by type
            Route::put('/taxations/{type}', [AdminController::class, 'updateTax'])->name('admin.taxations.update'); // Update tax
            Route::delete('/taxations/{id}', [AdminController::class, 'destroyTax'])->name('admin.taxations.destroy'); // Delete tax
        });

        // ------------------ Coupons ------------------
        Route::middleware('admin.module:Coupons')->group(function () {
            Route::get('/coupons', [AdminController::class, 'coupons'])->name('admin.coupons.coupons'); // List coupons
            Route::get('/coupons/create', [AdminController::class, 'createCoupon'])->name('admin.coupons.create'); // Create coupon
            Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store'); // Store coupon
            Route::get('/coupons/{id}/edit', [AdminController::class, 'editCoupon'])->name('admin.coupons.edit'); // Edit coupon
            Route::put('/coupons/{id}', [AdminController::class, 'updateCoupon'])->name('admin.coupons.update'); // Update coupon
            Route::delete('/coupons/{id}', [AdminController::class, 'destroyCoupon'])->name('admin.coupons.destroy'); // Delete coupon
        });

        // ------------------ Notifications ------------------
        Route::middleware('admin.module:Notifications')->group(function () {
            Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index'); // List notifications
            Route::get('/notifications/view/{id}', [NotificationController::class, 'view'])->name('admin.notifications.view'); // View notification
        });

    }); // End admin.auth middleware

}); // End admin prefix group
