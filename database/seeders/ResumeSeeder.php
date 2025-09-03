<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
       DB::table('resumes_format')->insert([
                    'resume' => <<<HTML
                            <!DOCTYPE html>
                            <html lang="en">
                            <head>
                            <meta charset="UTF-8" />
                            <title>Resume - Prajwal Ingole</title>
                            <style>
                            body {
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            line-height: 1.6;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
                            color: #333;
                            }
                            .container {
                            width: 80%;
                            max-width: 900px;
                            margin: 30px auto;
                            background: #fff;
                            padding: 30px;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                            }
                            .header {
                            text-align: center;
                            border-bottom: 2px solid #333;
                            padding-bottom: 15px;
                            }
                            .header h1 {
                            margin: 0;
                            font-size: 32px;
                            color: #2c3e50;
                            }
                            .header p {
                            margin: 5px 0;
                            font-size: 14px;
                            color: #777;
                            }
                            .section {
                            margin-top: 30px;
                            }
                            .section h2 {
                            color: #2c3e50;
                            margin-bottom: 10px;
                            border-bottom: 1px solid #ccc;
                            padding-bottom: 5px;
                            }
                            .section p, .section li {
                            font-size: 15px;
                            }
                            .section ul {
                            padding-left: 20px;
                            }
                            .job-title {
                            font-weight: bold;
                            color: #34495e;
                            }
                            .company {
                            font-style: italic;
                            color: #666;
                            }
                            .date {
                            float: right;
                            color: #888;
                            }
                            .skills span {
                            background-color: #e0e0e0;
                            padding: 5px 10px;
                            margin: 5px 5px 0 0;
                            display: inline-block;
                            border-radius: 5px;
                            }
                            .clearfix::after {
                            content: "";
                            display: table;
                            clear: both;
                            }
                            </style>
                            </head>
                            <body>
                            <div class="container">
                            <div class="header">
                                <h1>Prajwal Ingole</h1>
                                <p>Email: prajwal@example.com | Phone: +91-9975239057 | GitHub: github.com/prajwal</p>
                            </div>
                            <div class="section">
                                <h2>Professional Summary</h2>
                                <p>Motivated and detail-oriented web developer with 3+ years of experience building responsive websites and web apps. Proficient in front-end technologies like HTML, CSS, JavaScript and frameworks such as React and Laravel.</p>
                            </div>
                            <div class="section">
                                <h2>Work Experience</h2>
                                <div class="clearfix">
                                <p class="job-title">Frontend Developer <span class="date">Jan 2022 – Present</span></p>
                                <p class="company">ABC Tech Pvt. Ltd., Pune</p>
                                </div>
                                <ul>
                                <li>Developed modern web apps using React.js and Bootstrap.</li>
                                <li>Collaborated with designers and back-end developers for seamless UI/UX.</li>
                                <li>Implemented REST APIs and handled state management.</li>
                                </ul>
                                <div class="clearfix" style="margin-top:20px;">
                                <p class="job-title">Web Developer Intern <span class="date">Jun 2021 – Dec 2021</span></p>
                                <p class="company">XYZ Solutions, Nagpur</p>
                                </div>
                                <ul>
                                <li>Assisted in building and maintaining Laravel-based web apps.</li>
                                <li>Worked on UI improvements and bug fixes.</li>
                                </ul>
                            </div>
                            <div class="section">
                                <h2>Education</h2>
                                <p><strong>Bachelor of Engineering (Computer Science)</strong><br />
                                RTM Nagpur University, 2018 – 2022</p>
                            </div>
                            <div class="section">
                                <h2>Skills</h2>
                                <div class="skills">
                                <span>HTML5</span>
                                <span>CSS3</span>
                                <span>JavaScript</span>
                                <span>React.js</span>
                                <span>Laravel</span>
                                <span>Bootstrap</span>
                                <span>MySQL</span>
                                <span>Git</span>
                                <span>REST API</span>
                                </div>
                            </div>
                            <div class="section">
                            <h2>Projects</h2>
                            <ul>
                                <li><strong>Inventory Management System</strong>: Built a full-stack web app using React, Node.js, and MySQL for warehouse tracking.</li>
                                <li><strong>GoWash Laundry Platform</strong>: Created a laundry booking platform with user authentication and multi-service support using Laravel and Vue.js.</li>
                                <li><strong>Talentrek</strong>: Developed a complete jobseeker and recruiter platform with features like real-time chat, applicant tracking, resume uploads, and admin dashboards using Laravel, Alpine.js, and Tailwind CSS.</li>
                            </ul>
                            </div>
                            </div>
                            </body>
                            </html>
                            HTML,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

    }
}
