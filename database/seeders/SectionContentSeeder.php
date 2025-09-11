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
                'section' => 'Mobile Banner',
                'ar_section' => 'البانر الجوال',
                'slug' => 'banner',
                'heading' => 'Your Journey to Grow & Succeed Starts Here.',
                'ar_heading' => 'رحلتك للنمو والنجاح تبدأ هنا.',
                'description' => 'Improve your skills & engage with certified professional / industry leaders - anytime, anywhere.',
                'ar_description' => 'حسّن مهاراتك وتفاعل مع محترفين / قادة الصناعة المعتمدين - في أي وقت وأي مكان.',
                'file_name' => 'Banner.png',
                'file_path' => 'https://talentrek.reviewdevelopment.net/asset/images/banner/Banner.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Web Banner',
                'ar_section' => 'البانر الويب',
                'slug' => 'web_banner',
                'heading' => 'Improve your skills & engage with certified professional / industry leaders - anytime, anywhere.',
                'ar_heading' => 'حسّن مهاراتك وتفاعل مع محترفين / قادة الصناعة المعتمدين - في أي وقت وأي مكان.',
                'description' => '<div class="container mx-auto px-6 md:px-12 py-64 flex items-center">
                                    <div class="w-full md:w-1/2 text-white space-y-6">
                                    <h1 class="text-3xl md:text-5xl font-bold leading-tight text-white">
                                        Your Journey to <br />
                                        <span class="text-white">Grow & Succeed Starts Here</span>
                                    </h1>
                                    <p class="text-base text-gray-100 max-w-md" style="padding-bottom: 3%;">
                                    Improve your skills & engage with certified professional / industry leaders - anytime, anywhere
                                    </p>
                                    <a href="' . route('signin.form') . '" class="mt-6 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded text-sm">
                                        Sign In / Sign Up
                                    </a>
                                    </div>
                                </div>',
                'ar_description' => '<div class="container mx-auto px-6 md:px-12 py-64 flex items-center">
                                    <div class="w-full md:w-1/2 text-white space-y-6">
                                    <h1 class="text-3xl md:text-5xl font-bold leading-tight text-white">
                                        Your Journey to <br />
                                        <span class="text-white">Grow & Succeed Starts Here</span>
                                    </h1>
                                    <p class="text-base text-gray-100 max-w-md" style="padding-bottom: 3%;">
                                    Improve your skills & engage with certified professional / industry leaders - anytime, anywhere
                                    </p>
                                    <a href="' . route('signin.form') . '" class="mt-6 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded text-sm">
                                        Sign In / Sign Up
                                    </a>
                                    </div>
                                </div>',
                'file_name' => 'Banner.png',
                'file_path' => 'https://talentrek.reviewdevelopment.net/asset/images/banner/Banner.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'About Talentrek',
                'ar_section' => 'من نحن',
                'slug' => 'join-talentrek',
                'heading' => 'Join Talentrek as a Trainer, Mentor, Assessor, and Coach',
                'ar_heading' => 'انضم إلى Talentrek كمدرب، مرشد، مقيم، ومدرب',
                'description' => '<div class="lg:w-1/2">
                                    <h2 class="text-3xl md:text-4xl font-bold leading-snug">
                                    Join <span class="text-blue-600">Talentrek</span><br />
                                    as a Trainer, Mentor, Assessor, and Coach
                                    </h2>
                                    <p class="text-gray-700 mt-4 mb-6">
                                        Share your expertise, guide <span class="text-black font-bold">jobseeker/professional</span>, and one stop-shop powerful platform.
                                    </p>
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

                                    <a href="' . route('training') . '" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    Join Talentrek
                                    </a>
                                </div>',
                'ar_description' => '<div class="lg:w-1/2">
                                    <h2 class="text-3xl md:text-4xl font-bold leading-snug">
                                    Join <span class="text-blue-600">Talentrek</span><br />
                                    as a Trainer, Mentor, Assessor, and Coach
                                    </h2>
                                    <p class="text-gray-700 mt-4 mb-6">
                                        Share your expertise, guide <span class="text-black font-bold">jobseeker/professional</span>, and one stop-shop powerful platform.
                                    </p>
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

                                    <a href="' . route('training') . '" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    Join Talentrek
                                    </a>
                                </div>',
                'file_name' => 'teams.png',
                'file_path' => 'https://talentrek.reviewdevelopment.net/asset/images/gallery/teams.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Countings',
                'ar_section' => 'العدادات',
                'slug' => 'countings',
                'heading' => NULL,
                'ar_heading' => NULL,
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
                'ar_description' => '<div class="row text-center">
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Training Page(Course Overview)',
                'ar_section' => 'تفاصيل الدورة',
                'slug' => 'course-overview',
                'heading' => 'Course Overview',
                'ar_heading' => 'تفاصيل الدورة',
                'description' => "Unlock your creative potential with our Graphic Design course tailored for beginners and aspiring designers. Learn the fundamentals of design theory, master industry-standard tools like Adobe Photoshop, Illustrator, and Figma, and gain the confidence to create visually compelling designs for digital and print media. Whether you're starting fresh or leveling up, this course sets you on a professional design path.",
                'ar_description' => "اكتشف إمكانياتك الإبداعية من خلال دورة التصميم الجرافيكي لدينا المخصصة للمبتدئين والمصممين الطموحين. تعلم أساسيات نظرية التصميم وإتقان أدوات الصناعة مثل Photoshop وIllustrator وFigma واكتساب الثقة لإنشاء تصميمات جذابة بصريًا.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Training Page(Benefits of training)',
                'ar_section' => 'مميزات التدريب',
                'slug' => 'benefits-of-training',
                'heading' => 'Benefits of training',
                'ar_heading' => 'مميزات التدريب',
                'description' => "Enhance Creativity – Develop your artistic skills and turn ideas into visually compelling designs.
                                Master Industry Tools – Learn Photoshop, Illustrator, Figma, and other top design software used by professionals.
                                Career Opportunities – Open doors to roles like UI/UX Designer, Branding Expert, and Digital Illustrator.
                                Freelance & Business Growth – Work independently, start your own design agency, or take on freelance projects.
                                Effective Visual Communication – Learn how to design impactful branding, marketing materials, and user interfaces.
                                High Demand & Competitive Salaries – Graphic designers are always needed in advertising, tech, and digital media industries.
                                Build a Strong Portfolio – Work on real-world projects that showcase your skills and help you land jobs or clients.",
                'ar_description' => "تعزيز الإبداع – تطوير مهاراتك الفنية وتحويل الأفكار إلى تصميمات جذابة بصريًا.
                                إتقان أدوات الصناعة – تعلم Photoshop وIllustrator وFigma وبرامج التصميم الرائدة الأخرى.
                                فرص وظيفية – فتح أبواب لأدوار مثل مصمم UI/UX وخبير العلامة التجارية ورسّام رقمي.
                                العمل الحر ونمو الأعمال – العمل بشكل مستقل أو بدء وكالة تصميم خاصة بك.
                                التواصل البصري الفعّال – تعلم كيفية تصميم علامة تجارية ومواد تسويقية وواجهات مستخدم مؤثرة.
                                الطلب العالي والرواتب التنافسية – يحتاج السوق دائمًا لمصممي الجرافيك.
                                بناء ملف أعمال قوي – العمل على مشاريع حقيقية لعرض مهاراتك.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Mentorship Page(Mentorship overview)',
                'ar_section' => 'تفاصيل التدريب',
                'slug' => 'mentorship-overview',
                'heading' => 'Mentorship overview',
                'ar_heading' => 'نظرة عامة على الإرشاد',
                'description' => "As a mentor, you play a pivotal role in shaping the future of your mentees' careers. Your expertise, guidance, and support can have a profound impact on their professional growth and success.",
                'ar_description' => "بصفتك مرشدًا، تلعب دورًا محوريًا في تشكيل مستقبل حياتهم المهنية. يمكن لخبرتك وتوجيهك ودعمك أن يكون له تأثير كبير على نموهم ونجاحهم المهني.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Mentorship Page(Benefits of mentorship)',
                'ar_section' => 'مميزات التدريب',
                'slug' => 'benefits-of-mentorship',
                'heading' => 'Benefits of mentorship',
                'ar_heading' => 'مميزات الإرشاد',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptas?",
                'ar_description' => "لوريم إيبسوم نص افتراضي مستخدم في الطباعة.",
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Terms & Conditions',
                'ar_section' => 'الشروط والاحكام',
                'slug' => 'terms-and-conditions',
                'heading' => 'Terms & Conditions',
                'ar_heading' => 'الشروط والأحكام',
                'description' => '<div class="max-w-5xl mx-auto space-y-8">
                                    <p class="text-gray-700 text-base leading-relaxed">
                                        These Terms & Conditions (“<strong>Terms</strong>”) govern your use of Talentrek’s web application, services, and platform features as applicable to your role: Job Seeker, Trainer/Coach/Mentor/Assessor, or Recruiter. By registering or accessing Talentrek, you agree to be bound by these Terms.
                                    </p>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900">1. Definitions</h2>
                                        <p class="text-gray-700 text-base leading-relaxed">
                                            <strong>Talentrek (“we”, “us”, or “our”)</strong>: assists in connecting Job Seekers, Trainers/Coaches/Mentors/Assessors, and Recruiters for professional development, coaching, mentorship, and recruitment services.
                                        </p>
                                        <h3 class="text-xl font-semibold text-gray-800 mt-2">User Roles:</h3>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li><strong>Job Seeker:</strong> individual seeking training, coaching, or job-related services.</li>
                                            <li><strong>Trainer/Coach/Mentor/Assessor:</strong> individual or entity providing services to Job Seekers.</li>
                                            <li><strong>Recruiter:</strong> individual or entity sourcing talent through Talentrek.</li>
                                        </ul>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">2. Acceptance of Terms</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Use of Talentrek’s platform via website or application constitutes acceptance of these Terms. If you disagree, please do not use or register on Talentrek.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">3. User Eligibility & Account Responsibility</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            You represent that you are legally capable of entering into binding contracts. Minors must have legal guardian consent to register. Accounts are non‑transferable.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">4. Services & Content</h2>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Job Seekers may book sessions with Trainers/Coaches/Mentors/Assessors and access posted recruitment opportunities.</li>
                                            <li>Trainers/Coaches/Mentors/Assessors may offer services, set session terms, and receive payments.</li>
                                            <li>Recruiters may post job opportunities and connect with suitable candidates.</li>
                                        </ul>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Talentrek is not responsible for any professional outcomes. All intellectual property rights on the platform are owned by Talentrek or respective parties—not transferable.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">5. Subscription & Payment</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Users may access services through subscription plans. All payments are processed via secure payment gateways. Talentrek reserves the right to set or modify fees at its discretion.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">6. No Refund Policy</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            All purchases and subscriptions are final. No refunds will be issued under any circumstances once the subscription is activated or service is used.
                                        </p>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            This strict no‑refund policy ensures commitment and operational stability for all participants. Talentrek does not offer any refund, partial or full, once a subscription or payment is confirmed and service access is granted.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">7. Limitation of Liability</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            To the maximum extent allowed by law, Talentrek and its affiliates are not liable for indirect, incidental, or consequential damages arising from your use of the platform.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">8. Termination</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Talentrek reserves the right to suspend or terminate any User account for policy violations or inappropriate behavior. Termination by Talentrek does not void the No Refund policy.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">9. Amendments</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            We may update these Terms at any time. Continued use after changes constitutes acceptance.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">10. Governing Law & Dispute Resolution</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            These Terms are governed by [Choose jurisdiction; e.g., the laws of Damam, KSA]. Any disputes shall be resolved in [a designated legal forum].
                                        </p>
                                    </section>
                                </div>',
                'ar_description' => '<div class="max-w-5xl mx-auto space-y-8">
                                    <p class="text-gray-700 text-base leading-relaxed">
                                        These Terms & Conditions (“<strong>Terms</strong>”) govern your use of Talentrek’s web application, services, and platform features as applicable to your role: Job Seeker, Trainer/Coach/Mentor/Assessor, or Recruiter. By registering or accessing Talentrek, you agree to be bound by these Terms.
                                    </p>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900">1. Definitions</h2>
                                        <p class="text-gray-700 text-base leading-relaxed">
                                            <strong>Talentrek (“we”, “us”, or “our”)</strong>: assists in connecting Job Seekers, Trainers/Coaches/Mentors/Assessors, and Recruiters for professional development, coaching, mentorship, and recruitment services.
                                        </p>
                                        <h3 class="text-xl font-semibold text-gray-800 mt-2">User Roles:</h3>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li><strong>Job Seeker:</strong> individual seeking training, coaching, or job-related services.</li>
                                            <li><strong>Trainer/Coach/Mentor/Assessor:</strong> individual or entity providing services to Job Seekers.</li>
                                            <li><strong>Recruiter:</strong> individual or entity sourcing talent through Talentrek.</li>
                                        </ul>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">2. Acceptance of Terms</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Use of Talentrek’s platform via website or application constitutes acceptance of these Terms. If you disagree, please do not use or register on Talentrek.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">3. User Eligibility & Account Responsibility</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            You represent that you are legally capable of entering into binding contracts. Minors must have legal guardian consent to register. Accounts are non‑transferable.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">4. Services & Content</h2>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Job Seekers may book sessions with Trainers/Coaches/Mentors/Assessors and access posted recruitment opportunities.</li>
                                            <li>Trainers/Coaches/Mentors/Assessors may offer services, set session terms, and receive payments.</li>
                                            <li>Recruiters may post job opportunities and connect with suitable candidates.</li>
                                        </ul>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Talentrek is not responsible for any professional outcomes. All intellectual property rights on the platform are owned by Talentrek or respective parties—not transferable.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">5. Subscription & Payment</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Users may access services through subscription plans. All payments are processed via secure payment gateways. Talentrek reserves the right to set or modify fees at its discretion.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">6. No Refund Policy</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            All purchases and subscriptions are final. No refunds will be issued under any circumstances once the subscription is activated or service is used.
                                        </p>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            This strict no‑refund policy ensures commitment and operational stability for all participants. Talentrek does not offer any refund, partial or full, once a subscription or payment is confirmed and service access is granted.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">7. Limitation of Liability</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            To the maximum extent allowed by law, Talentrek and its affiliates are not liable for indirect, incidental, or consequential damages arising from your use of the platform.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">8. Termination</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            Talentrek reserves the right to suspend or terminate any User account for policy violations or inappropriate behavior. Termination by Talentrek does not void the No Refund policy.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">9. Amendments</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            We may update these Terms at any time. Continued use after changes constitutes acceptance.
                                        </p>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">10. Governing Law & Dispute Resolution</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">
                                            These Terms are governed by [Choose jurisdiction; e.g., the laws of Damam, KSA]. Any disputes shall be resolved in [a designated legal forum].
                                        </p>
                                    </section>
                                </div>',
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'section' => 'Privacy Policy',
                'ar_section' => 'سياسة الخصوصية',
                'slug' => 'privacy-policy',
                'heading' => 'Privacy Policy',
                'ar_heading' => 'سياسة الخصوصية',
                'description' => '<div class="max-w-5xl mx-auto space-y-8">
                                    <p class="text-gray-700 text-base leading-relaxed">
                                        This Privacy Policy explains how GMQ Consulting (<strong>“Company”</strong>, <strong>“We”</strong>, <strong>“Us”</strong>) collects, uses, and discloses your information when you use the Talentrek Service (<strong>“Service”</strong>). It also outlines your privacy rights and how the law protects you.
                                    </p>
                                    <p class="text-gray-700 text-base leading-relaxed">
                                        By using our Service, you agree to the collection and use of information in accordance with this Privacy Policy.
                                    </p>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900">1. Interpretation and Definitions</h2>
                                        <p class="text-gray-700 text-base leading-relaxed"><strong>Interpretation:</strong> Capitalized words have specific meanings defined here. These definitions apply whether in singular or plural form.</p>
                                        <h3 class="text-xl font-semibold text-gray-800 mt-2">Definitions:</h3>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li><strong>Account:</strong> A unique account created for you to access our Service.</li>
                                            <li><strong>Application:</strong> The Talentrek software downloaded on your device.</li>
                                            <li><strong>Company:</strong> GMQ Consulting, referred to as “We”, “Us”, or “Our”.</li>
                                            <li><strong>Country:</strong> Kingdom of Saudi Arabia (KSA).</li>
                                            <li><strong>Cookies:</strong> Small files stored on your device that track browsing history and preferences.</li>
                                            <li><strong>Device:</strong> Any device accessing the Service, such as a computer, phone, or tablet.</li>
                                            <li><strong>Personal Data:</strong> Information relating to an identified or identifiable individual.</li>
                                            <li><strong>Service:</strong> The Talentrek website and application.</li>
                                            <li><strong>Service Provider:</strong> Third-party companies or individuals who process data on our behalf.</li>
                                            <li><strong>Usage Data:</strong> Data collected automatically, e.g., pages visited and time spent.</li>
                                            <li><strong>You:</strong> The individual or entity using the Service.</li>
                                        </ul>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">2. Collecting and Using Your Personal Data</h2>
                                        
                                        <h3 class="text-xl font-semibold text-gray-800 mt-4">2.1 Types of Data Collected</h3>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2"><strong>Personal Data:</strong> When using our Service, we may ask for information including, but not limited to:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Name, email, and phone number</li>
                                            <li>Address, city, state, ZIP code</li>
                                            <li>Usage Data</li>
                                        </ul>

                                        <p class="text-gray-700 text-base leading-relaxed mt-4"><strong>Usage Data:</strong> Automatically collected information may include:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>IP address, browser type and version, device information</li>
                                            <li>Pages visited, time and date of visit, time spent</li>
                                            <li>Mobile device details, operating system, and unique identifiers</li>
                                        </ul>

                                        <p class="text-gray-700 text-base leading-relaxed mt-4"><strong>Information from the Application:</strong> With your permission, we may collect location data to provide and improve features. You can enable or disable access in your device settings.</p>

                                        <h3 class="text-xl font-semibold text-gray-800 mt-6">2.2 Use of Your Personal Data</h3>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We may use your information to:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Provide, maintain, and improve our Service</li>
                                            <li>Manage your Account and registration</li>
                                            <li>Fulfill contracts for products or services</li>
                                            <li>Communicate updates, notifications, offers, or marketing</li>
                                            <li>Manage and respond to your requests</li>
                                            <li>Conduct business transfers (mergers, acquisitions, or sales)</li>
                                            <li>Analyze usage trends and improve Service performance</li>
                                        </ul>

                                        <p class="text-gray-700 text-base leading-relaxed mt-4"><strong>Sharing of Personal Data:</strong></p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Service Providers: For analytics, payment, and communication</li>
                                            <li>Business Transfers: During mergers, acquisitions, or sales</li>
                                            <li>Affiliates: To operate and improve Service while following this Privacy Policy</li>
                                            <li>Business Partners: To provide certain products, services, or promotions</li>
                                            <li>Other Users: Publicly shared information may be visible to all users</li>
                                            <li>Consent: With your consent, for other purposes</li>
                                        </ul>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">3. Retention of Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We retain Personal Data only as long as necessary to provide the Service, comply with legal obligations, and resolve disputes. Usage Data is retained for internal analysis, security, and Service improvement.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">4. Transfer of Your Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">Your information may be transferred to and stored in locations outside your jurisdiction. By using our Service, you consent to such transfers. We take reasonable steps to ensure your data is secure.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">5. Disclosure of Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We may disclose Personal Data for:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Business Transactions: Mergers, acquisitions, or asset sales</li>
                                            <li>Law Enforcement: Complying with legal requirements or court orders</li>
                                            <li>Other Legal Requirements: Protecting rights, property, safety, or preventing wrongdoing</li>
                                        </ul>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">6. Security of Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We use commercially reasonable measures to protect your data but cannot guarantee absolute security.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">7. Third-Party Services</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">Third-party vendors may collect, store, and process information according to their own privacy policies. You may opt-out of promotional emails using unsubscribe links or by contacting us.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">8. Children’s Privacy</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">Our Service is not for individuals under 13. If we collect data from a child under 13 without parental consent, we will delete it promptly.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">9. Links to Other Websites</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We are not responsible for the privacy practices of third-party websites. Review their policies before providing personal information.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">10. Changes to This Privacy Policy</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We may update this Privacy Policy and will notify you via email or a prominent notice on the Service. Review periodically for updates.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">11. Contact Us</h2>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2 mb-8">
                                            <li>Visit: <a href="https://talentrek.reviewdevelopment.net/" class="text-blue-600 underline">https://talentrek.reviewdevelopment.net/</a></li>
                                            <li>Email: <a href="mailto:info@gmqconsulting.com" class="text-blue-600 underline">info@gmqconsulting.com</a></li>
                                        </ul>
                                    </section>
                                </div>',
                'ar_description' => '<div class="max-w-5xl mx-auto space-y-8">
                                    <p class="text-gray-700 text-base leading-relaxed">
                                        This Privacy Policy explains how GMQ Consulting (<strong>“Company”</strong>, <strong>“We”</strong>, <strong>“Us”</strong>) collects, uses, and discloses your information when you use the Talentrek Service (<strong>“Service”</strong>). It also outlines your privacy rights and how the law protects you.
                                    </p>
                                    <p class="text-gray-700 text-base leading-relaxed">
                                        By using our Service, you agree to the collection and use of information in accordance with this Privacy Policy.
                                    </p>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900">1. Interpretation and Definitions</h2>
                                        <p class="text-gray-700 text-base leading-relaxed"><strong>Interpretation:</strong> Capitalized words have specific meanings defined here. These definitions apply whether in singular or plural form.</p>
                                        <h3 class="text-xl font-semibold text-gray-800 mt-2">Definitions:</h3>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li><strong>Account:</strong> A unique account created for you to access our Service.</li>
                                            <li><strong>Application:</strong> The Talentrek software downloaded on your device.</li>
                                            <li><strong>Company:</strong> GMQ Consulting, referred to as “We”, “Us”, or “Our”.</li>
                                            <li><strong>Country:</strong> Kingdom of Saudi Arabia (KSA).</li>
                                            <li><strong>Cookies:</strong> Small files stored on your device that track browsing history and preferences.</li>
                                            <li><strong>Device:</strong> Any device accessing the Service, such as a computer, phone, or tablet.</li>
                                            <li><strong>Personal Data:</strong> Information relating to an identified or identifiable individual.</li>
                                            <li><strong>Service:</strong> The Talentrek website and application.</li>
                                            <li><strong>Service Provider:</strong> Third-party companies or individuals who process data on our behalf.</li>
                                            <li><strong>Usage Data:</strong> Data collected automatically, e.g., pages visited and time spent.</li>
                                            <li><strong>You:</strong> The individual or entity using the Service.</li>
                                        </ul>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">2. Collecting and Using Your Personal Data</h2>
                                        
                                        <h3 class="text-xl font-semibold text-gray-800 mt-4">2.1 Types of Data Collected</h3>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2"><strong>Personal Data:</strong> When using our Service, we may ask for information including, but not limited to:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Name, email, and phone number</li>
                                            <li>Address, city, state, ZIP code</li>
                                            <li>Usage Data</li>
                                        </ul>

                                        <p class="text-gray-700 text-base leading-relaxed mt-4"><strong>Usage Data:</strong> Automatically collected information may include:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>IP address, browser type and version, device information</li>
                                            <li>Pages visited, time and date of visit, time spent</li>
                                            <li>Mobile device details, operating system, and unique identifiers</li>
                                        </ul>

                                        <p class="text-gray-700 text-base leading-relaxed mt-4"><strong>Information from the Application:</strong> With your permission, we may collect location data to provide and improve features. You can enable or disable access in your device settings.</p>

                                        <h3 class="text-xl font-semibold text-gray-800 mt-6">2.2 Use of Your Personal Data</h3>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We may use your information to:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Provide, maintain, and improve our Service</li>
                                            <li>Manage your Account and registration</li>
                                            <li>Fulfill contracts for products or services</li>
                                            <li>Communicate updates, notifications, offers, or marketing</li>
                                            <li>Manage and respond to your requests</li>
                                            <li>Conduct business transfers (mergers, acquisitions, or sales)</li>
                                            <li>Analyze usage trends and improve Service performance</li>
                                        </ul>

                                        <p class="text-gray-700 text-base leading-relaxed mt-4"><strong>Sharing of Personal Data:</strong></p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Service Providers: For analytics, payment, and communication</li>
                                            <li>Business Transfers: During mergers, acquisitions, or sales</li>
                                            <li>Affiliates: To operate and improve Service while following this Privacy Policy</li>
                                            <li>Business Partners: To provide certain products, services, or promotions</li>
                                            <li>Other Users: Publicly shared information may be visible to all users</li>
                                            <li>Consent: With your consent, for other purposes</li>
                                        </ul>
                                    </section>
                                    <section class="space-y-4">
                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">3. Retention of Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We retain Personal Data only as long as necessary to provide the Service, comply with legal obligations, and resolve disputes. Usage Data is retained for internal analysis, security, and Service improvement.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">4. Transfer of Your Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">Your information may be transferred to and stored in locations outside your jurisdiction. By using our Service, you consent to such transfers. We take reasonable steps to ensure your data is secure.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">5. Disclosure of Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We may disclose Personal Data for:</p>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2">
                                            <li>Business Transactions: Mergers, acquisitions, or asset sales</li>
                                            <li>Law Enforcement: Complying with legal requirements or court orders</li>
                                            <li>Other Legal Requirements: Protecting rights, property, safety, or preventing wrongdoing</li>
                                        </ul>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">6. Security of Personal Data</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We use commercially reasonable measures to protect your data but cannot guarantee absolute security.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">7. Third-Party Services</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">Third-party vendors may collect, store, and process information according to their own privacy policies. You may opt-out of promotional emails using unsubscribe links or by contacting us.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">8. Children’s Privacy</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">Our Service is not for individuals under 13. If we collect data from a child under 13 without parental consent, we will delete it promptly.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">9. Links to Other Websites</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We are not responsible for the privacy practices of third-party websites. Review their policies before providing personal information.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">10. Changes to This Privacy Policy</h2>
                                        <p class="text-gray-700 text-base leading-relaxed mt-2">We may update this Privacy Policy and will notify you via email or a prominent notice on the Service. Review periodically for updates.</p>

                                        <h2 class="text-2xl font-semibold text-gray-900 mt-6">11. Contact Us</h2>
                                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-base leading-relaxed mt-2 mb-8">
                                            <li>Visit: <a href="https://talentrek.reviewdevelopment.net/" class="text-blue-600 underline">https://talentrek.reviewdevelopment.net/</a></li>
                                            <li>Email: <a href="mailto:info@gmqconsulting.com" class="text-blue-600 underline">info@gmqconsulting.com</a></li>
                                        </ul>
                                    </section>
                                </div>',
                'file_name' => NULL,
                'file_path' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
