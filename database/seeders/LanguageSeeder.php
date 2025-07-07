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
        ];


        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
