<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

          $this->call([
            AdminSeeder::class,
            JobseekerInformationSeeder::class,
            SocialMediaSeeder::class,
            SiteSettingsSeeder::class,
            SectionContentSeeder::class,
            RecruitersSeeder::class,
            RecruitersCompanySeeder::class,
            TrainerSeeder::class,
            AssessorSeeder::class,
            CoachSeeder::class,
            TestimonialSeeder::class,
            MentorSeeder::class,

            TrainingMaterialsTableSeeder::class,
            CertificateTemplateSeeder::class,
            EducationDetailsSeeder::class,
            TrainingBatchSeeder::class,
            TrainingMaterialsDocumentsSeeder::class,
            TrainerAssessmentsTableSeeder::class,
            AssessmentQuestionsTableSeeder::class,
            AssessmentOptionsTableSeeder::class,
            LanguageSeeder::class,
            ResumeSeeder::class,
            TrainingCategorySeeder::class,
            ReviewSeeder::class,
            BookingSlotsSeeder::class,
            PaymentSeeder::class,
            JobseekerSavedBookingSessionSeeder::class,
            JobseekerTrainingMaterialPurchasesSeeder::class,
            JobseekerCartItemSeeder::class,
        ]);
        
    }
}
