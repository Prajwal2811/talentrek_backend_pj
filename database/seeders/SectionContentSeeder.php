<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;
class SectionContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_module')->insert([
            [
                'section' => 'Banner',
                'slug' => 'banner',
                'heading' => 'Your Journey to Grow & Succeed Starts Here',
                'description' => '<div class="container mx-auto px-6 md:px-12 py-64 flex items-center">
                                    <div class="w-full md:w-1/2 text-white space-y-6">
                                    <h1 class="text-3xl md:text-5xl font-bold leading-tight text-white">
                                        Your Journey to <br />
                                        <span class="text-white">Grow & Succeed Starts Here</span>
                                    </h1>
                                    <p class="text-base text-gray-100 max-w-md">
                                        Earn certificates and gain new skills with trusted educators and industry leaders—anytime, anywhere.
                                    </p>
                                    <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded text-sm">
                                        Sign In / Sign Up
                                    </button>
                                    </div>
                                </div>',
                'file_name' => 'Banner.png',
                'file_path' => 'https://talentrek.reviewdevelopment.net/asset/images/banner/Banner.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section' => 'About Talentrek',
                'slug' => 'join-talentrek',
                'heading' => 'Join Talentrek as a Trainer, Mentor, Assessor, and Coach',
                'description' => ' <div class="lg:w-1/2">
                                        <h2 class="text-3xl md:text-4xl font-bold leading-snug">
                                        Join <span class="text-blue-600">Talentrek</span><br />
                                        as a Trainer, Mentor, Assessor, and Coach
                                        </h2>
                                        <p class="text-gray-700 mt-4 mb-6">
                                        Share your expertise, guide jobseeker/professional, and one stop-shop powerful platform. 
                                        </p>

                                        <!-- Bullet Buttons with Circle Check Icon -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                                Empower Learners
                                            </div>
                                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                                Earn & Grow
                                            </div>
                                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                                    Flexible Engagement
                                            </div>
                                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                                Expand Your Reach
                                            </div>
                                        </div>


                                        <!-- CTA Button -->
                                        <a href="#" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                        Join Talentrek
                                        </a>
                                    </div>',
                'file_name' => 'teams.png',
                'file_path' => 'https://talentrek.reviewdevelopment.net/asset/images/gallery/teams.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section' => 'Countings',
                'slug' => 'countings',
                'heading' => NUll,
                'description' => '<div class="row text-center">
                                    <div class="col-md-4 mb-4 mb-md-0">
                                        <div class="stats-number">35000+</div>
                                        <div class="stats-description">Student worldwide</div>
                                    </div>
                                    <div class="col-md-4 mb-4 mb-md-0">
                                        <div class="stats-number">500+</div>
                                        <div class="stats-description">Course available</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stats-number">10000+</div>
                                        <div class="stats-description">People loved it</div>
                                    </div>
                                </div>',
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section' => 'Training Page(Course Overview)',
                'slug' => 'course-overview',
                'heading' => 'Course Overview',
                'description' => "Unlock your creative potential with our Graphic Design course tailored for beginners and aspiring designers. Learn the fundamentals of design theory, master industry-standard tools like Adobe Photoshop, Illustrator, and Figma, and gain the confidence to create visually compelling designs for digital and print media. Whether you're starting fresh or leveling up, this course sets you on a professional design path.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section' => 'Training Page(Benefits of training)',
                'slug' => 'benefits-of-training',
                'heading' => 'Benefits of training',
                'description' => "Enhance Creativity – Develop your artistic skills and turn ideas into visually compelling designs.
                                Master Industry Tools – Learn Photoshop, Illustrator, Figma, and other top design software used by professionals.
                                Career Opportunities – Open doors to roles like UI/UX Designer, Branding Expert, and Digital Illustrator.
                                Freelance & Business Growth – Work independently, start your own design agency, or take on freelance projects.
                                Effective Visual Communication – Learn how to design impactful branding, marketing materials, and user interfaces.
                                High Demand & Competitive Salaries – Graphic designers are always needed in advertising, tech, and digital media industries.
                                Build a Strong Portfolio – Work on real-world projects that showcase your skills and help you land jobs or clients.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section' => 'Mentorship Page(Mentorship overview)',
                'slug' => 'mentorship-overview',
                'heading' => 'Mentorship overview',
                'description' => "As a mentor, you play a pivotal role in shaping the future of your mentees' careers. Your expertise, guidance, and support can have a profound impact on their professional growth and success.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'section' => 'Mentorship Page(Benefits of mentorship)',
                'slug' => 'benefits-of-mentorship',
                'heading' => 'Benefits of mentorship',
                'description' => "lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptas?",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
