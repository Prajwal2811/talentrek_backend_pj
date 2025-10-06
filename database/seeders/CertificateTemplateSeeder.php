<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificateTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $html = <<<EOT
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <title>Jobseeker Certificate</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
            <style>
                body {
                background-color: #f0f0f0;
                font-family: 'Georgia', serif;
                }

                .certificate {
                max-width: 900px;
                margin: 50px auto;
                padding: 60px;
                background-color: #fff;
                border: 8px solid #2c3e50;
                box-shadow: 0 0 20px rgba(0,0,0,0.2);
                text-align: center;
                }

                .certificate h1 {
                font-size: 2.8rem;
                color: #2c3e50;
                margin-bottom: 20px;
                }

                .certificate h2 {
                font-size: 1.6rem;
                margin-bottom: 30px;
                color: #444;
                }

                .certificate .jobseeker-name {
                font-size: 2.2rem;
                font-weight: bold;
                color: #000;
                border-bottom: 2px solid #000;
                display: inline-block;
                padding: 5px 30px;
                margin-bottom: 25px;
                }

                .certificate .course-title {
                font-size: 1.4rem;
                color: #1a1a1a;
                font-weight: bold;
                margin-bottom: 5px;
                }

                .certificate .job-role {
                font-size: 1.2rem;
                color: #555;
                margin-bottom: 20px;
                }

                .certificate .details {
                font-size: 1.2rem;
                color: #333;
                margin-top: 15px;
                }

                .certificate .footer {
                margin-top: 60px;
                display: flex;
                justify-content: space-between;
                padding: 0 50px;
                }

                .sign {
                text-align: center;
                }

                .sign hr {
                margin: 10px auto;
                width: 200px;
                border-top: 2px solid #000;
                }

                .logo {
                width: 90px;
                margin-bottom: 15px;
                }
            </style>
            </head>
            <body>

            <div class="certificate">
                <img src="https://pixelvalues.com/wp-content/uploads/2022/03/logo.png.webp" alt="Company Logo" class="logo" />
                <h1>Certificate of Achievement</h1>
                <h2>This certificate is proudly presented to</h2>

                <div class="jobseeker-name">Prajwal Ingole</div>

                <div class="course-title">Software Development Program</div>
                <div class="job-role">(as a Fullstack Deveoplerw)</div>

                <div class="details">
                Conducted by <strong>Talentrek</strong><br />
                From <strong>1st May 2025</strong> to <strong>30th June 2025</strong><br />
                In recognition of outstanding performance, dedication, and learning commitment.
                </div>

                <div class="footer mt-5">
                <div class="sign">
                    <hr />
                    <p><strong>HR Manager</strong><br />Anjali Sharma</p>
                </div>
                <div class="sign">
                    <hr />
                    <p><strong>CEO</strong><br />Rahul Verma</p>
                </div>
                </div>
            </div>

            </body>
            </html>
            EOT;

        DB::table('certificate_template')->insert([
            'certificate_title' => 'Job Completion Certificate',
            'template_html' => $html,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
