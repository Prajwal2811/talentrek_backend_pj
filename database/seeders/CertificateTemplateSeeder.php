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
            <title>Certificate - Sahil Khan</title>
            <link rel="stylesheet" href="style.css" />
            <style>
            .certificate-container {
            position: relative;
            width: 952px;
            height: 671px;
            margin: 40px auto;
            background-image: url('/asset/images/Talentrek-Certificate-blank.png');
            background-size: cover;
            background-position: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }
            .text-layer {
            position: absolute;
            top: 130px;
            left: 75px;
            width: 750px;
            color: #000;
            text-align: left;
            }
            /* CERTIFICATE OF COMPLETION */
            #certificate-title {
            font-size: 22px;
            font-weight: bold;
            font-family: 'Georgia', serif;
            margin-bottom: 30px;
            }
            /* "This is to certify that" */
            #certify-text {
            font-size: 16px;
            font-weight: normal;
            margin: 0 0 10px;
            }
            /* Name (blue) */
            #recipient-name {
            font-size: 26px;
            font-weight: bold;
            color: #0070c0;
            margin: 0 0 10px;
            }
            /* "has successfully..." */
            #success-text {
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 20px;
            }
            /* Course title */
            #course-title {
            font-size: 22px;
            font-weight: bold;
            font-family: 'Arial Black', sans-serif;
            margin: 10px 0 5px;
            }
            /* "Conducted by..." */
            #conducted-by {
            font-size: 16px;
            font-style: italic;
            margin-bottom: 30px;
            }
            /* Details section */
            .details p {
            font-size: 15px;
            margin: 6px 0;
            line-height: 1.5;
            }
            .details .label {
            font-weight: bold;
            }
            </style>
            <div class="certificate-container">
            <div class="text-layer">
            <h1 id="certificate-title">CERTIFICATE OF COMPLETION</h1>
            <p id="certify-text">This is to certify that</p>
            <p id="recipient-name">Sahil Khan</p>
            <p id="success-text">has successfully completed the training</p>
            <p id="course-title">Full Stack Development (Advance)</p>
            <p id="conducted-by">Conducted by Talentrek</p>
            <div class="details">
            <p><span class="label">Trainer:</span> John Doe (Full stack developer)</p>
            <p><span class="label">Course Duration:</span> 3 Months</p>
            <p><span class="label">Assessment Status:</span> Passed</p>
            <p><span class="label">Certificate ID:</span> T00254PBP</p>
            <p><span class="label">Completion Date:</span> 18 June 2025</p>
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
