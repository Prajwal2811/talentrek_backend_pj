<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;


class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run(): void
    {
       $languages  = [
            ['code' => 'name', 'english' => 'Name', 'arabic' => 'الاسم'],
            ['code' => 'email', 'english' => 'Email', 'arabic' => 'البريد الإلكتروني'],
            ['code' => 'phone', 'english' => 'Phone', 'arabic' => 'رقم الهاتف'],
            ['code' => 'address', 'english' => 'Address', 'arabic' => 'العنوان'],
            ['code' => 'city', 'english' => 'City', 'arabic' => 'المدينة'],
            ['code' => 'country', 'english' => 'Country', 'arabic' => 'الدولة'],
            ['code' => 'dob', 'english' => 'Date of Birth', 'arabic' => 'تاريخ الميلاد'],
            ['code' => 'gender', 'english' => 'Gender', 'arabic' => 'الجنس'],
            ['code' => 'password', 'english' => 'Password', 'arabic' => 'كلمة المرور'],
            ['code' => 'confirm_password', 'english' => 'Confirm Password', 'arabic' => 'تأكيد كلمة المرور'],
            ['code' => 'training', 'english' => 'Training', 'arabic' => 'تمرين'],
            ['code' => 'mentorship', 'english' => 'Mentorship', 'arabic' => 'الإرشاد'],
            ['code' => 'assessment', 'english' => 'Assessment', 'arabic' => 'تقدير'],
            ['code' => 'coaching', 'english' => 'Coaching', 'arabic' => 'التدريب'],
            ['code' => 'notifications', 'english' => 'Notifications', 'arabic' => 'إشعارات'],
            ['code' => 'view_all', 'english' => 'View All', 'arabic' => 'عرض الكل'],
            ['code' => 'view_records_found', 'english' => 'No Records Found', 'arabic' => 'لم يتم العثور على سجلات'],
            ['code' => 'profile', 'english' => 'Profile', 'arabic' => 'حساب تعريفي'],
            ['code' => 'my_profile', 'english' => 'My Profile', 'arabic' => 'ملفي الشخصي'],
            ['code' => 'logout', 'english' => 'Logout', 'arabic' => 'تسجيل الخروج'],
            ['code' => 'sign_in_sign_up', 'english' => 'Sign in / Sign up', 'arabic' => 'تسجيل الدخول / التسجيل'],
            ['code' => 'sign_in_as_jobseeker', 'english' => 'Sign in as Jobseeker', 'arabic' => 'تسجيل الدخول كباحث عن عمل'],
            ['code' => 'sign_in_as_mentor', 'english' => 'Sign in as Jobseeker', 'arabic' => 'تسجيل الدخول كباحث عن عمل'],
            ['code' => 'sign_in_as_trainer', 'english' => 'Sign in as Jobseeker', 'arabic' => 'تسجيل الدخول كباحث عن عمل'],
            ['code' => 'sign_in_as_assessor', 'english' => 'Sign in as Jobseeker', 'arabic' => 'تسجيل الدخول كباحث عن عمل'],
            ['code' => 'sign_in_as_coach', 'english' => 'Sign in as Jobseeker', 'arabic' => 'تسجيل الدخول كباحث عن عمل'],
            ['code' => 'remember_me', 'english' => 'Remember Me', 'arabic' => 'تذكرني'],
            ['code' => 'forgot_password', 'english' => 'Forgot Password?', 'arabic' => 'هل نسيت كلمة المرور؟'],
            ['code' => 'reset_password', 'english' => 'Reset Password', 'arabic' => 'إعادة تعيين كلمة المرور'],
            ['code' => 'send_password_reset_link', 'english' => 'Send Password Reset Link', 'arabic' => 'إرسال رابط إعادة تعيين كلمة المرور'],
            ['code' => 'dashboard', 'english' => 'Dashboard', 'arabic' => 'لوحة القيادة'],
            ['code' => 'welcome', 'english' => 'Welcome', 'arabic' => 'أهلا بك'],
            ['code' => 'edit_profile', 'english' => 'Edit Profile', 'arabic' => 'تعديل الملف الشخصي'],
            ['code' => 'change_password', 'english' => 'Change Password', 'arabic' => 'تغيير كلمة المرور'],
            ['code' => 'current_password', 'english' => 'Current Password', 'arabic' => 'كلمة المرور الحالية'],
            ['code' => 'new_password', 'english' => 'New Password', 'arabic' => 'كلمة مرور جديدة'],
            ['code' => 'confirm_new_password', 'english' => 'Confirm New Password', 'arabic' => 'تأكيد كلمة المرور الجديدة'],
            ['code' => 'update_password', 'english' => 'Update Password', 'arabic' => 	'تحديث كلمة المرور'],
            ['code' => 'save_changes', 'english' => 	'Save Changes', 	'arabic' => 	'حفظ التغييرات'],
            ['code' => 'cancel', 'english' => 'Cancel', 'arabic' => 'إلغاء'],
            ['code' => 'profile_updated_successfully', 'english' => 'Profile updated successfully', 'arabic' => 'تم تحديث الملف الشخصي بنجاح'],
            ['code' => 'password_updated_successfully', 'english' => 'Password updated successfully', 'arabic' => 'تم تحديث كلمة المرور بنجاح'],
            ['code' => 'invalid_current_password', 'english' => 'Invalid current password', 'arabic' => 'كلمة المرور الحالية غير صالحة'],
            
        ];


        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
