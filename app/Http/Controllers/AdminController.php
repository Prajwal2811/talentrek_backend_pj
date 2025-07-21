<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\CMS;
use App\Models\Coach;
use App\Models\Assessors;
use App\Models\RecruiterCompany;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\AdditionalInfo;
use App\Models\Testimonial;
use App\Models\Trainers;
use App\Models\Language;
use App\Models\Resume;
use App\Models\Review;
use App\Models\JobseekerTrainingMaterialPurchase;
use App\Models\PaymentHistory   ;
use App\Models\Mentors;
use App\Models\BookingSession;
use App\Models\RecruiterJobseekersShortlist;
use App\Models\TrainingBatch;
use App\Models\TrainerAssessment;
use App\Models\TrainingMaterialsDocument;
use App\Models\CertificateTemplate;
use App\Models\TrainingMaterial;
use App\Models\TrainingCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use DB;
class AdminController extends Controller
{
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 'active'
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->get('remember'))) {
            $admin = Auth::guard('admin')->user();

            Log::info('Admin login successful', [
                'email' => $admin->email,
                'name'  => $admin->name,
                'role'  => $admin->role, // assuming 'role' is a column like 'admin' or 'superadmin'
                'time'  => now()
            ]);

            return redirect()->route('admin.dashboard');
        } else {
            Log::warning('Admin login failed', [
                'email' => $request->email,
                'time'  => now()
            ]);

            session()->flash('error', 'Either Email/Password is incorrect');
            return back()->withInput($request->only('email'));
        }
    }


    public function sendResetPassword(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        try {
            // Find the admin
            $admin = Admin::where('email', $request->email)->first();

            if ($admin) {
                // Extract username part from email
                $username = strstr($admin->email, '@', true);

                // Create new password
                $newPassword = $username . '@talentrek';

                // Update password
                $admin->password = Hash::make($newPassword);
                $admin->pass = $newPassword;
                $admin->save();

                // Log password reset
                Log::info('Password reset for admin: ' . $admin->email, [
                    'admin_id' => $admin->id,
                    'new_password' => $newPassword, // ⚠️ For production, remove or mask this
                    'timestamp' => now()
                ]);

                // Send email
                Mail::html(
                    '
                        <!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <title>Password Reset</title>
                                <style>
                                    body {
                                        background-color: #f6f8fa;
                                        font-family: Arial, sans-serif;
                                        padding: 20px;
                                        margin: 0;
                                        color: #333;
                                    }
                                    .container {
                                        background-color: #ffffff;
                                        padding: 30px;
                                        max-width: 500px;
                                        margin: 20px auto;
                                        border-radius: 8px;
                                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                    }
                                    .otp-box {
                                        font-size: 24px;
                                        font-weight: bold;
                                        background-color: #f0f4ff;
                                        padding: 15px;
                                        text-align: center;
                                        border: 1px dashed #007bff;
                                        border-radius: 6px;
                                        margin: 20px 0;
                                        color: #007bff;
                                    }
                                    .footer {
                                        font-size: 12px;
                                        text-align: center;
                                        margin-top: 30px;
                                        color: #888;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <h2>Password Reset Successful</h2>
                                    <p>Hello, ' . $admin->name . '!</p>
                                    <p>Your new password has been generated successfully. Use the password below to log in:</p>

                                    <div class="otp-box">' . $newPassword . '</div>

                                    <p>For your security, we recommend changing this password after logging in.</p>

                                    <p>Thanks,<br><strong>The Talentrek Team</strong></p>
                                </div>

                                <div class="footer">
                                    &copy; ' . date('Y') . ' Talentrek. All rights reserved.
                                </div>
                            </body>
                            </html>
                    ',
                    function ($message) use ($admin) {
                        $message->to($admin->email)->subject('Your New Password – Talentrek');
                    }
                );

                return back()->with('success', 'A new password has been sent to your email.');
            }

            Log::warning('Password reset attempted for non-existent admin email: ' . $request->email);
            return back()->with('error', 'Admin not found.');
        } catch (\Exception $e) {
            Log::error('Error during password reset: ' . $e->getMessage(), [
                'email' => $request->email,
                'timestamp' => now()
            ]);
            return back()->with('error', 'An error occurred while resetting the password.');
        }
    }


    public function signOut()
    {
        // Capture admin info before logout
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            Log::info('Admin logged out', [
                'email' => $admin->email,
                'name'  => $admin->name,
                'role'  => $admin->role, // assuming 'role' is a column like 'admin' or 'superadmin'
                'time'  => now()
            ]);
        }

        Auth::guard('admin')->logout(); // Logs out the admin user
        session()->flash('success', 'You have been logged out successfully.');
        
        return redirect()->route('admin.login'); // Redirect to named route
    }

    public function dashboard()
    {
        $jobseekerCount = Jobseekers::where('status', 'active')->count();
        $recruiterCount = Recruiters::where('status', 'active')->count();
        $trainerCount   = Trainers::where('status', 'active')->count();
        // $expatCount     = Expats::where('status', 'active')->count();
        $coachCount     = Coach::where('status', 'active')->count();
        $mentorCount    = Mentors::where('status', 'active')->count();
        $assessorCount  = Assessors::where('status', 'active')->count();

        // Example logic for revenue and session counts
        $materialSales = JobseekerTrainingMaterialPurchase::select('.')
                                                        ->join('payments_history', 'jobseeker_training_material_purchases.payment_id', '=', 'payments_history.id')
                                                        ->where('payments_history.payment_status', 'paid')
                                                        ->sum('amount_paid');

        // $mentorSessionCount = Bookings::where('type', 'mentor')->count();
        // $coachSessionCount = Bookings::where('type', 'coach')->count();
        // $assessorSessionCount = Bookings::where('type', 'assessor')->count();


        $roleCounts = [
            'Jobseeker' => Jobseekers::count(),
            'Recruiter' => Recruiters::count(),
            'Trainer' => Trainers::count(),
            // 'Expat' => Expats::count(),
            'Coach' => Coach::count(),
            'Mentor' => Mentors::count(),
            'Assessor' => Assessors::count(),
        ];

        $months = collect(range(1, 12)); // All 12 months

        $currentYear = now()->year;

        $jobseekerData = $months->map(fn($month) =>
            Jobseekers::whereMonth('created_at', $month)->whereYear('created_at', $currentYear)->count()
        );

        $recruiterData = $months->map(fn($month) =>
            Recruiters::whereMonth('created_at', $month)->whereYear('created_at', $currentYear)->count()
        );

        $trainerData = $months->map(fn($month) =>
            Trainers::whereMonth('created_at', $month)->whereYear('created_at', $currentYear)->count()
        );


        
        return view('admin.dashboard', [
            'jobseekerCount'        => $jobseekerCount,
            'recruiterCount'        => $recruiterCount,
            'trainerCount'          => $trainerCount,
            // 'expatCount'            => $expatCount,
            'coachCount'            => $coachCount,
            'mentorCount'           => $mentorCount,
            'assessorCount'         => $assessorCount,
            'materialSales'         => $materialSales,
            // 'mentorSessionCount'    => $mentorSessionCount,
            // 'coachSessionCount'     => $coachSessionCount,
            // 'assessorSessionCount'  => $assessorSessionCount,
            'roleCounts'            => $roleCounts,
            'jobseekerData' => $jobseekerData,
            'recruiterData' => $recruiterData,
            'trainerData' => $trainerData,
        ]);
    }

    public function create()
    {
        return view('admin.admin.create');
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $validator = Validator::make($request->all(), [
            'full_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:admins,email',
            'password'          => 'required|string|min:6',
            'confirm_password'  => 'required|same:password',
            'notes'             => 'nullable|string',
            'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create new Admin
        $admin = new Admin();
        $admin->name = $request->input('full_name');
        $admin->email = $request->input('email');
        $admin->password = Hash::make($request->input('password'));
        $admin->pass = $request->input('password'); // ❗️Don't store raw passwords in production
        $admin->notes = $request->input('notes');
        $admin->role = 'admin';
        $admin->status = 'active';
        $admin->permissions = $request->permissions; // auto-cast to JSON
        $admin->save();

        // Fetch current user details
        $currentUser = auth()->user();

        // Log the creation with detailed info
        Log::info('New admin account created', [
            'action_by_email' => $currentUser?->email ?? 'system',
            'action_by_name' => $currentUser?->name ?? 'System',
            'action_by_role' => $currentUser?->role ?? 'unknown',
            'new_admin_email' => $admin->email,
            'new_admin_name' => $admin->name,
            'time' => now()
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin created successfully!');
    }


    public function index()
    {   
        $admins = Admin::where('role', 'admin')->get();
        return view('admin.admin.index', compact('admins'));
    }


    public function destroy($id)
    {
        // Find the admin by ID
        $admin = Admin::findOrFail($id);

        // Capture info before deleting
        $deletedAdminInfo = [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
        ];

        // Delete the admin
        $admin->delete();

        // Logged-in user info
        $actor = auth()->user();

        // Log the deletion
        Log::warning('Admin account deleted', [
            'deleted_admin' => $deletedAdminInfo,
            'deleted_by_name' => $actor?->name ?? 'System',
            'deleted_by_email' => $actor?->email ?? 'system',
            'deleted_by_role' => $actor?->role ?? 'unknown',
            'time' => now()
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }


    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = Admin::findOrFail($validated['admin_id']);
        $previousStatus = $user->status;
        $user->status = $validated['status'];
        $user->save();

        // Get the actor (admin or superadmin who performed the action)
        $actor = auth()->user();

        // Log the status change
        Log::info('Admin status changed', [
            'target_admin' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'previous_status' => $previousStatus,
                'new_status' => $user->status
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Admin status updated successfully.',
            'status' => $user->status
        ]);
    }


    public function assignAdmin(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'jobseeker_ids' => 'required|string',
        ]);
        $adminId = $validated['admin_id'];
        $jobseekerIds = explode(',', $validated['jobseeker_ids']);
        $assignedCount = 0;
        $assignedJobseekers = [];
        
        foreach ($jobseekerIds as $jobseekerId) {
            $jobseeker = Jobseekers::find($jobseekerId);
            
            // Only assign if not already assigned to an admin
            if ($jobseeker) {
                $jobseeker->assigned_admin = $adminId;
                $jobseeker->save();

                $assignedCount++;
                $assignedJobseekers[] = [
                    'id' => $jobseeker->id,
                    'name' => $jobseeker->name,
                    'email' => $jobseeker->email ?? null,
                ];
            }
        }

        // Fetch assigned admin info
        $assignedAdmin = Admin::find($adminId);

        // Get actor info (who made the assignment)
        $actor = auth()->user();

        // Log the assignment
        Log::info('Jobseekers assigned to admin', [
            'assigned_to_admin' => [
                'id' => $assignedAdmin->id,
                'name' => $assignedAdmin->name,
                'email' => $assignedAdmin->email,
                'role' => $assignedAdmin->role,
            ],
            'assigned_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown',
            ],
            'jobseekers_assigned' => $assignedJobseekers,
            'total_assigned' => $assignedCount,
            'time' => now()
        ]);

        return redirect()->back()->with('success', 'Jobseekers have been successfully assigned to the admin.');
    }

    


    public function unassign(Request $request)
    {
        $jobseeker = Jobseekers::find($request->id);

        if ($jobseeker) {
            $previousAdminId = $jobseeker->assigned_admin;

            // Fetch previous admin info (if exists)
            $previousAdmin = $previousAdminId ? Admin::find($previousAdminId) : null;

            // Perform unassignment
            $jobseeker->assigned_admin = null;
            $jobseeker->save();

            // Get actor info
            $actor = auth()->user();

            // Log the unassignment
            Log::info('Jobseeker unassigned from admin', [
                'jobseeker' => [
                    'id' => $jobseeker->id,
                    'name' => $jobseeker->name,
                    'email' => $jobseeker->email ?? null,
                ],
                'previous_admin' => $previousAdmin ? [
                    'id' => $previousAdmin->id,
                    'name' => $previousAdmin->name,
                    'email' => $previousAdmin->email,
                    'role' => $previousAdmin->role
                ] : 'None',
                'unassigned_by' => [
                    'id' => $actor?->id ?? null,
                    'name' => $actor?->name ?? 'System',
                    'email' => $actor?->email ?? 'system',
                    'role' => $actor?->role ?? 'unknown'
                ],
                'time' => now()
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admin.edit', compact('admin'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'notes' => 'nullable|string',
            'permissions' => 'nullable|array', // ✅ validate permissions array
        ]);

        $admin = Admin::findOrFail($id);

        // Save old data for logging
        $oldData = $admin->only(['name', 'email', 'notes', 'permissions']);

        // Update admin
        $admin->name = $request->input('full_name');
        $admin->email = $request->input('email');
        $admin->notes = $request->input('notes');
        $admin->permissions = $request->input('permissions', []); // ✅ save permissions
        $admin->save();

        // Log activity
        $actor = auth()->user();
        Log::info('Admin profile updated', [
            'target_admin' => [
                'id' => $admin->id,
                'old_data' => $oldData,
                'new_data' => $admin->only(['name', 'email', 'notes', 'permissions']),
            ],
            'updated_by' => [
                'id' => $actor?->id,
                'name' => $actor?->name,
                'email' => $actor?->email,
                'role' => $actor?->role,
            ],
            'time' => now(),
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin updated successfully.');
    }


    public function jobseekers()
    {   
        $admin = Auth::guard('admin')->user();
        $adminId = $admin->id;
        // If the user is a superadmin, show all jobseekers
        if ($admin->role === 'superadmin') {
            $jobseekers = Jobseekers::orderBy('id', 'desc')
                                    ->get();
        } else {
            // Else show only jobseekers assigned to this admin
            $jobseekers = Jobseekers::where('assigned_admin', $adminId)
                                    ->orderBy('id', 'desc')
                                    ->get();
        }

        $admins = Admin::where('role', 'admin')->get();

        return view('admin.jobseeker.index', compact('jobseekers', 'admins'));
    }



    public function jobseekerChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'jobseeker_id' => 'required|exists:jobseekers,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = Jobseekers::findOrFail($validated['jobseeker_id']);
        $oldStatus = $user->status;
        $oldReason = $user->inactive_reason;

        $user->status = $validated['status'];

        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason'];
        } else {
            $user->inactive_reason = null;
        }

        $user->save();

        // Actor performing the change
        $actor = auth()->user();

        // Logging the change
        Log::info('Jobseeker status updated', [
            'jobseeker' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? null,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'old_reason' => $oldReason,
                'new_reason' => $user->inactive_reason
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Jobseeker status updated successfully.',
            'status' => $user->status
        ]);
    }


    public function jobseekerView($id)
    {
        $jobseeker = Jobseekers::findOrFail($id);
        $educations = $jobseeker->educations()->orderBy('id', 'desc')->get();
        $experiences = $jobseeker->experiences()->orderBy('id', 'desc')->get();
        $skills = $jobseeker->skills()->orderBy('id', 'desc')->get();
        $additioninfos = AdditionalInfo::select('*')->where('user_id' , $id)->where('user_type','jobseeker')->get();
        // print_r($additioninfos); die;    
        return view('admin.jobseeker.view', compact('jobseeker', 'experiences', 'educations', 'skills','additioninfos'));
    }


    public function updateStatus(Request $request)
    {
        $request->validate([
            'jobseeker_id' => 'required|exists:jobseekers,id',
            'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
            'reason' => 'nullable|string|max:1000',
        ]);

        $jobseeker = Jobseekers::findOrFail($request->jobseeker_id);
        $user = auth()->user();
        $previousStatus = $jobseeker->admin_status;
        $status = $request->status;

        // Role validation
        if ($user->role === 'superadmin' && !Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Invalid status for superadmin');
        } elseif ($user->role === 'admin' && Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Admins cannot perform superadmin actions');
        } elseif (!in_array($user->role, ['admin', 'superadmin'])) {
            Log::warning('Unauthorized jobseeker status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'jobseeker_id' => $jobseeker->id,
                'time' => now(),
            ]);
            return back()->with('error', 'Unauthorized action.');
        }

        // Update status and rejection reason
        $jobseeker->admin_status = $status;
        $jobseeker->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
        $jobseeker->save();

        // Log the update
        Log::info('Jobseeker admin status updated', [
            'jobseeker' => [
                'id' => $jobseeker->id,
                'name' => $jobseeker->name,
                'email' => $jobseeker->email,
                'previous_status' => $previousStatus,
                'new_status' => $status,
                'rejection_reason' => $jobseeker->rejection_reason,
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'time' => now(),
        ]);

        // Send email if rejected
        if (Str::endsWith($status, 'rejected') && $request->filled('reason') && $jobseeker->email) {
            Mail::html('
                <!DOCTYPE html>
                <html>
                <head><meta charset="UTF-8"><title>Application Rejected – Talentrek</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px; color: #333; }
                    .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 30px; }
                    .reason { background-color: #ffe6e6; border-left: 4px solid #dc3545; padding: 10px 15px; margin: 15px 0; }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Application Update – <span style="color:#dc3545;">Rejected</span></h2>
                        </div>
                        <p>Hi <strong>' . e($jobseeker->name ?? $jobseeker->email) . '</strong>,</p>
                        <p>We regret to inform you that your application on <strong>Talentrek</strong> has been rejected.</p>
                        <div class="reason"><strong>Reason:</strong> ' . e($request->reason) . '</div>
                        <p>If you believe this was a mistake, contact <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>
                        <p>Thank you,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">© ' . date('Y') . ' Talentrek. All rights reserved.</div>
                </body>
                </html>
            ', function ($message) use ($jobseeker) {
                $message->to($jobseeker->email)->subject('Application Rejected – Talentrek');
            });
        }

        return back()->with('success', 'Status updated successfully.');
    }





    public function recruiters()
    {   
        $recruiters = Recruiters::select('recruiters.*','recruiters_company.*','recruiters_company.id as company_id')
                                ->join('recruiters_company','recruiters.id','=','recruiters_company.recruiter_id')
                                ->orderBy('recruiters.id', 'desc')
                                ->get();
    //    echo "<pre>"; print_r($recruiters); exit;
        return view('admin.recruiter.index', compact('recruiters'));
    }


    public function recruiterChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:recruiters_company,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = RecruiterCompany::findOrFail($validated['company_id']);
        $oldStatus = $user->status;
        $oldReason = $user->inactive_reason;

        $user->status = $validated['status'];

        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason'];
        } else {
            $user->inactive_reason = null;
        }

        $user->save();

        // Actor performing the change
        $actor = auth()->user();
        // Logging the change
        Log::info('Recruiter status updated', [
            'recruiter' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? null,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'old_reason' => $oldReason,
                'new_reason' => $user->inactive_reason
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Recruiter status updated successfully.',
            'status' => $user->status
        ]);
    }

    public function recruiterView($id)
    {
        $recruiter = Recruiters::findOrFail($id);
        $company = $recruiter->company; 
        $additioninfos = AdditionalInfo::select('*')->where('user_id' , $id)->where('user_type','recruiter')->get();
        // print_r($recruiter); die;    
        return view('admin.recruiter.view', compact('recruiter', 'company', 'additioninfos'));
    }

    public function viewShortlistedJobseekers($id)
    {
        // echo "<pre>"; print_r($id); die;
        $shortlistJobseekers = RecruiterJobseekersShortlist::select('recruiter_jobseeker_shortlist.*','jobseekers.*','recruiter_jobseeker_shortlist.admin_status as admin_status_rjs')->where('recruiter_id', $id)
                                                            ->join('jobseekers', 'recruiter_jobseeker_shortlist.jobseeker_id', '=', 'jobseekers.id')
                                                            ->get();
        // echo "<pre>";  print_r($shortlistJobseekers); die ;    
        return view('admin.recruiter.shortlisted-jobseekers', compact('shortlistJobseekers'));
    }

    public function updateStatusForShortlist(Request $request)
    {
        $request->validate([
            'jobseeker_id' => 'required|exists:jobseekers,id',
            'status' => 'required|in:approved,rejected',
            'reason' => 'nullable|string|max:500',
            'role' => 'required|in:admin,superadmin',
        ]);

        $jobseeker = RecruiterJobseekersShortlist::where('jobseeker_id', $request->jobseeker_id)->firstOrFail();

        if ($request->role === 'admin') {
            $jobseeker->admin_status = $request->status;
            $jobseeker->rejection_reason = $request->status === 'rejected' ? $request->reason : null;
        } elseif ($request->role === 'superadmin') {
            if ($jobseeker->admin_status === 'approved') {
                $jobseeker->admin_status = 'superadmin_' . $request->status;
                $jobseeker->rejection_reason = $request->status === 'rejected' ? $request->reason : null;
            }
        }

        $jobseeker->save();

        return back()->with('success', 'Status updated.');
    }



    public function updateRecruiterStatus(Request $request)
{
    $request->validate([
        'company_id' => 'required|exists:recruiters_company,id',
        'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
        'reason' => 'nullable|string|max:1000',
    ]);

    $recruiter = RecruiterCompany::findOrFail($request->company_id);
    $user = auth()->user();
    $previousStatus = $recruiter->admin_status;
    $status = $request->status;

    // Role validation
    if ($user->role === 'superadmin') {
        if (!Str::startsWith($status, 'superadmin_')) {
            return response()->json(['success' => false, 'message' => 'Invalid status for superadmin'], 403);
        }

        if ($recruiter->admin_status === 'rejected') {
            return response()->json(['success' => false, 'message' => 'Cannot override admin rejection'], 403);
        }

    } elseif ($user->role === 'admin') {
        if (Str::startsWith($status, 'superadmin_')) {
            return response()->json(['success' => false, 'message' => 'Admins cannot perform superadmin actions'], 403);
        }

    } else {
        Log::warning('Unauthorized recruiter status update attempt', [
            'attempted_by' => $user->only(['id', 'name', 'email', 'role']),
            'recruiter_id' => $recruiter->id,
            'time' => now(),
        ]);
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    // Save status
    $recruiter->admin_status = $status;
    $recruiter->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
    $recruiter->save();

    Log::info('Recruiter status updated', [
        'recruiter' => $recruiter->only(['id', 'name', 'email']),
        'previous_status' => $previousStatus,
        'new_status' => $status,
        'rejection_reason' => $recruiter->rejection_reason,
        'updated_by' => $user->only(['id', 'name', 'email', 'role']),
        'time' => now(),
    ]);

    // Email on rejection
    if (Str::endsWith($status, 'rejected') && $request->filled('reason') && $recruiter->email) {
        Mail::html('
                <!DOCTYPE html>
                <html>
                <head><meta charset="UTF-8"><title>Recruiter Application Rejected</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px; color: #333; }
                    .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 30px; }
                    .reason { background-color: #ffe6e6; border-left: 4px solid #dc3545; padding: 10px 15px; margin: 15px 0; }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Recruiter Application – <span style="color:#dc3545;">Rejected</span></h2>
                        </div>
                        <p>Hi <strong>' . e($recruiter->name ?? $recruiter->email) . '</strong>,</p>
                        <p>Your recruiter registration has been rejected on <strong>Talentrek</strong>.</p>
                        <div class="reason"><strong>Reason:</strong> ' . e($request->reason) . '</div>
                        <p>If you have questions, contact <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>
                        <p>Thank you,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">© ' . date('Y') . ' Talentrek. All rights reserved.</div>
                </body>
                </html>
            ', function ($message) use ($recruiter) {
                $message->to($recruiter->email)->subject('Recruiter Application Rejected – Talentrek');
            });
    }

    return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
}

    public function cms()
    {
        $cmsmodules = CMS::get();
        return view('admin.cms.index', compact('cmsmodules'));
    }

    public function cmsEdit($slug)
    {
        $cms = CMS::where('slug', $slug)->first();
        return view('admin.cms.edit', compact('cms'));
    }

    public function updateBanner(Request $request)
    {
        // Validate inputs
        $request->validate([
            'slug'         => 'required|string|exists:cms_module,slug',
            'heading'      => 'string|max:255',
            'description'  => 'string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            // Fetch existing CMS banner by slug
            $cms = CMS::where('slug', $request->slug)->firstOrFail();

            // Handle new file upload and delete old file
            if ($request->hasFile('banner_image')) {
                $fileName = $request->file('banner_image')->getClientOriginalName();
                $extension = $request->file('banner_image')->getClientOriginalExtension();
                $fileNameToStore  = 'banner_image_' . date('Ymdhis') . '.' . $extension;
                $path = asset('uploads/' . $fileNameToStore);
                $request->file('banner_image')->move('uploads/', $fileNameToStore);
                // $decryptedId = $request->input('id');
                CMS::where('slug', $cms->slug)->update(['file_path' => $path, 'file_name' => $fileName]);
            }

            // Update text fields
            $cms->heading     = $request->heading;
            $cms->description = $request->description;

            // Save record
            $cms->save();

           return redirect()->back()->with('success', $cms->slug === 'banner' ? 'Banner updated successfully!' : 'Section data updated successfully!');


        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    } 



    public function settings()
    {
        return view('admin.settings.index');
    }


    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'header_logo'  => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'footer_logo'  => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'favicon_icon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp,svg|max:1024',
        ]);

        $setting = Setting::find($request->input('id')) ?? new Setting();

        // Upload folder
        $uploadPath = public_path('uploads');

        // Handle header logo
        if ($request->hasFile('header_logo')) {
            if (!empty($setting->header_logo) && file_exists(public_path($setting->header_logo))) {
                unlink(public_path($setting->header_logo));
            }
            $headerLogo = $request->file('header_logo');
            $headerLogoName = 'header_logo_' . time() . '.' . $headerLogo->getClientOriginalExtension();
            $headerLogo->move($uploadPath, $headerLogoName);
            $setting->header_logo = 'uploads/' . $headerLogoName;
        }

        // Handle footer logo
        if ($request->hasFile('footer_logo')) {
            if (!empty($setting->footer_logo) && file_exists(public_path($setting->footer_logo))) {
                unlink(public_path($setting->footer_logo));
            }
            $footerLogo = $request->file('footer_logo');
            $footerLogoName = 'footer_logo_' . time() . '.' . $footerLogo->getClientOriginalExtension();
            $footerLogo->move($uploadPath, $footerLogoName);
            $setting->footer_logo = 'uploads/' . $footerLogoName;
        }

        // Handle favicon
        if ($request->hasFile('favicon_icon')) {
            if (!empty($setting->favicon) && file_exists(public_path($setting->favicon))) {
                unlink(public_path($setting->favicon));
            }
            $favicon = $request->file('favicon_icon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move($uploadPath, $faviconName);
            $setting->favicon = 'uploads/' . $faviconName;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }


    public function resume()
    {
        return view('admin.resume.index');
    }


    public function resumeUpdate(Request $request)
    {
        $request->validate([
            'resume' => 'required',
        ]);

        $resume = Resume::find($request->input('id')) ?? new Resume();
        $resume->resume = $request->input('resume');
        $resume->save();

        return redirect()->route('admin.resume.download.option', ['id' => $resume->id])
                        ->with('success', 'Resume format uploaded successfully.');
    }




    public function storeMediaLinks(Request $request)
    {
        $request->validate([
            'media_name.*' => 'required|string|max:100',
            'icon_class.*' => 'nullable|string|max:100',
            'media_link.*' => 'required|url|max:255',
        ]);

        // Optional: Clear existing links before saving new ones
        SocialMedia::truncate();

        // Save all entries
        foreach ($request->media_name as $index => $name) {
            SocialMedia::create([
                'media_name' => $name,
                'icon_class' => $request->icon_class[$index] ?? null,
                'media_link' => $request->media_link[$index],
            ]);
        }

        return back()->with('success', 'Social media links saved successfully.');
    }


    public function testimonials(){
        $testimonials = Testimonial::all();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function createTestimonial(){
        return view('admin.testimonials.create');
    }



    public function storeTestimonial(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'message' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $testimonial = new Testimonial();
        $testimonial->name = $validated['name'];
        $testimonial->designation = $validated['designation'];
        $testimonial->message = $validated['message'];

        // Handle image upload if present
        if ($request->hasFile('profile_picture')) {
                $fileName = $request->file('profile_picture')->getClientOriginalName();
                $filePath = 'profile_picture_' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $request->file('profile_picture')->move('uploads/', $filePath);
                $testimonial->file_name = $fileName;
                $testimonial->file_path = asset('uploads/' . $filePath);
        }

        $testimonial->save();

        return redirect()->route('admin.testimonials')->with('success', 'Testimonial added successfully!');
    }

    public function editTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }


    public function updateTestimonial(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'message' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Find testimonial
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->name = $validated['name'];
        $testimonial->designation = $validated['designation'];
        $testimonial->message = $validated['message'];

        // Handle image upload if present
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');

            // Get original name for reference
            $originalName = $image->getClientOriginalName();

            // Generate unique filename
            $uniqueName = 'profile_picture_' . time() . '.' . $image->getClientOriginalExtension();

            // Move file to public/uploads/
            $image->move(public_path('uploads'), $uniqueName);

            // Store in database
            $testimonial->file_name = $originalName;
            $testimonial->file_path = asset('uploads/' . $uniqueName); // relative path for use with asset()
        }


        $testimonial->save();

        return redirect()->route('admin.testimonials')->with('success', 'Testimonial updated successfully!');
    }

    public function destroyTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        return redirect()->route('admin.testimonials')->with('success', 'Testimonial deleted successfully!');
    }


    public function trainers()
    {
        $trainers = Trainers::all();
        return view('admin.trainers.index', compact('trainers'));
    }


    public function trainerChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = Trainers::findOrFail($validated['trainer_id']);
        $oldStatus = $user->status;
        $oldReason = $user->inactive_reason;

        $user->status = $validated['status'];

        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason'];
        } else {
            $user->inactive_reason = null;
        }

        $user->save();

        // Actor performing the change
        $actor = auth()->user();
        // Logging the change
        Log::info('Trainer status updated', [
            'trainer' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? null,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'old_reason' => $oldReason,
                'new_reason' => $user->inactive_reason
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Trainer status updated successfully.',
            'status' => $user->status
        ]);
    }

    public function viewTrainer($id)
    {
        $trainer = Trainers::findOrFail($id);
        $educations = $trainer->educations()->orderBy('id', 'desc')->get();
        $experiences = $trainer->experiences()->orderBy('id', 'desc')->get();
        $experience = $trainer->experience()->orderBy('id', 'desc')->get();
        $additioninfos = AdditionalInfo::select('*')->where('user_id' , $id)->where('user_type','trainer')->get();
        return view('admin.trainers.view', compact('trainer', 'educations', 'experiences', 'experience','additioninfos'));
    }


    public function viewTrainingMaterial($id)
    {
        $materials = TrainingMaterial::select('training_materials_documents.*','training_materials.*','training_materials.id as material_id')
                                    ->where('training_materials.trainer_id',$id)
                                    ->leftJoin('training_materials_documents', 'training_materials_documents.training_material_id','training_materials.id')        
                                    ->get();
        
        return view('admin.trainers.material.training-material', compact('materials'));
    }

    public function viewTrainingMaterialDetail($trainerId, $materialId)
    {
        $course = TrainingMaterial::select('*')->where('trainer_id', $trainerId)
                                    ->where('id', $materialId)
                                    ->firstOrFail();

        $batches = TrainingBatch::select('*')->where('trainer_id', $trainerId)->get();

        $courseDocuments = TrainingMaterialsDocument::select('*')->where('trainer_id', $trainerId)->where('training_material_id', $materialId)->get();
        
        $trainer = Trainers::find($trainerId);
        // echo "<pre>"; print_r($trainer); die;
        // echo "<pre>"; print_r($courseDocuments); die;
        return view('admin.trainers.material.training-material-detail', compact('course', 'batches','courseDocuments','trainer'));
    }


  public function trainingMaterialChangeStatus(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:training_materials,id',
            'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
            'reason' => 'nullable|string|max:1000',
        ]);

        $course = TrainingMaterial::findOrFail($request->course_id);
        $user = auth()->user();
        $status = $request->status;

        // Prevent further changes if superadmin decision is already made
        if (Str::startsWith($course->admin_status, 'superadmin_')) {
            return response()->json(['message' => 'Final decision already made.'], 422);
        }

        // Superadmin can act only after admin approval
        if ($user->role === 'superadmin') {
            if (!Str::startsWith($status, 'superadmin_') || $course->admin_status !== 'approved') {
                return response()->json(['message' => 'Superadmin can only act after admin approval.'], 422);
            }
        }

        // Admin cannot perform superadmin actions
        if ($user->role === 'admin' && Str::startsWith($status, 'superadmin_')) {
            return response()->json(['message' => 'Admin cannot perform superadmin actions.'], 422);
        }

        // Update status and reason
        $course->admin_status = $status;
        $course->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
        $course->save();

        // Send rejection email (HTML) if applicable
        if (Str::endsWith($status, 'rejected') && $request->filled('reason')) {
            $trainer = $course->trainer ?? Trainers::find($course->trainer_id);

            if ($trainer && $trainer->email) {
                $trainerName = $trainer->name ?? $trainer->email;
                $reason = $request->reason;
                $year = date('Y');

                $html = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='UTF-8'>
                        <title>Application Rejected – Talentrek</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f6f8fa;
                                padding: 20px;
                                color: #333;
                            }
                            .container {
                                background: #fff;
                                padding: 30px;
                                border-radius: 8px;
                                max-width: 600px;
                                margin: auto;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                            }
                            .header {
                                text-align: center;
                                margin-bottom: 20px;
                            }
                            .reason {
                                background-color: #ffe6e6;
                                border-left: 4px solid #dc3545;
                                padding: 10px 15px;
                                margin: 20px 0;
                            }
                            .footer {
                                font-size: 12px;
                                color: #999;
                                text-align: center;
                                margin-top: 30px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h2>Application <span style='color: #dc3545;'>Rejected</span></h2>
                            </div>

                            <p>Hello <strong>{$trainerName}</strong>,</p>

                            <p>We regret to inform you that your application on <strong>Talentrek</strong> has been rejected.</p>

                            <div class='reason'>
                                <strong>Reason:</strong><br>
                                {$reason}
                            </div>

                            <p>If you believe this was a mistake, feel free to contact us at <a href='mailto:support@talentrek.com'>support@talentrek.com</a>.</p>

                            <p>Best regards,<br><strong>Talentrek Team</strong></p>

                            <div class='footer'>
                                © {$year} Talentrek. All rights reserved.
                            </div>
                        </div>
                    </body>
                    </html>
                ";

                Mail::html($html, function ($message) use ($trainer) {
                    $message->to($trainer->email)
                            ->subject('Application Rejected – Talentrek');
                });
            }
        }

        return response()->json(['message' => 'Status updated.']);
    }



    public function updateStatusTrainer(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
            'reason' => 'nullable|string|max:1000',
        ]);

        $trainer = Trainers::findOrFail($request->trainer_id);
        $user = auth()->user();
        $previousStatus = $trainer->admin_status;
        $status = $request->status;

        // Role validation
        if ($user->role === 'superadmin' && !Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Invalid status for superadmin');
        } elseif ($user->role === 'admin' && Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Admins cannot perform superadmin actions');
        } elseif (!in_array($user->role, ['admin', 'superadmin'])) {
            Log::warning('Unauthorized trainer status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'trainer_id' => $trainer->id,
                'time' => now(),
            ]);
            return back()->with('error', 'Unauthorized action.');
        }

        // Update status and rejection reason
        $trainer->admin_status = $status;
        $trainer->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
        $trainer->save();

        // Log the update
        Log::info('Rrainer admin status updated', [
            'trainer' => [
                'id' => $trainer->id,
                'name' => $trainer->name,
                'email' => $trainer->email,
                'previous_status' => $previousStatus,
                'new_status' => $status,
                'rejection_reason' => $trainer->rejection_reason,
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'time' => now(),
        ]);

        // Send email if rejected
        if (Str::endsWith($status, 'rejected') && $request->filled('reason') && $trainer->email) {
            Mail::html('
                <!DOCTYPE html>
                <html>
                <head><meta charset="UTF-8"><title>Application Rejected – Talentrek</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px; color: #333; }
                    .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 30px; }
                    .reason { background-color: #ffe6e6; border-left: 4px solid #dc3545; padding: 10px 15px; margin: 15px 0; }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Application Update – <span style="color:#dc3545;">Rejected</span></h2>
                        </div>
                        <p>Hi <strong>' . e($trainer->name ?? $trainer->email) . '</strong>,</p>
                        <p>We regret to inform you that your application on <strong>Talentrek</strong> has been rejected.</p>
                        <div class="reason"><strong>Reason:</strong> ' . e($request->reason) . '</div>
                        <p>If you believe this was a mistake, contact <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>
                        <p>Thank you,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">© ' . date('Y') . ' Talentrek. All rights reserved.</div>
                </body>
                </html>
            ', function ($message) use ($trainer) {
                $message->to($trainer->email)->subject('Application Rejected – Talentrek');
            });
        }

        return back()->with('success', 'Status updated successfully.');
    }


    public function viewTrainerAssessment($id)
    {
        $assessments = TrainerAssessment::select('trainer_assessments.*')
                                        ->where('trainer_assessments.trainer_id' , $id)
                                        ->get();
        // echo "<pre>"; print_r($assessments); die;
        return view('admin.trainers.assessment.training-assessment', compact('assessments'));
    }

    
    public function viewTrainingAssessmentDetail($trainerId, $assessmentId)
    {
        // echo "<pre>"; print_r($trainerId); die;

        $assessment = TrainerAssessment::where('trainer_id', $trainerId)
                            ->where('id', $assessmentId)
                            ->with(['questions.options', 'course']) // Load questions with their options and course
                            ->firstOrFail();
        // echo "<pre>"; print_r($assessment); die;
        return view('admin.trainers.assessment.training-assessment-detail', compact('assessment'));

    }

    public function certificationTemplate() {
        return view('admin.certificate.index');
    }


    public function updateTemplate(Request $request)
    {

        // Validate the incoming request
        $request->validate([
            'description' => 'required|string',
        ]);

        // Assuming the certificate ID is passed as hidden input or route parameter
        $certificateId = $request->input('id'); // or retrieve from route if using route-model binding
        $certificate = CertificateTemplate::findOrFail($certificateId);

        // Update the HTML content
        $certificate->template_html = $request->input('description');
        $certificate->save();

        return redirect()->back()->with('success', 'Certificate template updated successfully.');
    }


    public function languages() {
        $language = Language::all();
        return view('admin.languages.index', compact('language'));
    }

    public function updateLanguage(Request $request)
    {
        $language = Language::findOrFail($request->id);
        $language->english = $request->english;
        $language->arabic = $request->arabic;
        $language->save();

        return response()->json([
            'success' => true,
            'message' => 'Language updated successfully.'
        ]);
    }



    public function contactSupport()
    {
        return view('admin.contact-support');
    }


    public function reviews()
    {
        $reviews = Review::select('reviews.*', 'jobseekers.name as reviewer_name','reviews.id as review_id')
                    ->join('jobseekers', 'reviews.jobseeker_id', '=', 'jobseekers.id')
                    ->whereIn('reviews.user_type', ['trainer', 'mentor', 'coach', 'assessor'])
                    ->get();

        // echo "<pre>"; print_r($reviews); die;
        return view('admin.reviews.index', compact('reviews'));
    }


    public function viewReview($id)
    {
        $review = Review::select('reviews.*', 'jobseekers.name as reviewer_name', 'jobseekers.email as reviewer_email')
            ->join('jobseekers', 'reviews.jobseeker_id', '=', 'jobseekers.id')
            ->whereIn('reviews.user_type', ['trainer', 'mentor', 'coach', 'assessor'])
            ->where('reviews.id', $id)
            ->first();

        // Initialize variables
        $revieweeName = null;
        $materialTitle = null;

        switch ($review->user_type) {
            case 'trainer':
                $reviewee = DB::table('trainers')->where('id', $review->user_id)->first();
                $revieweeName = $reviewee->name ?? 'N/A';

                // Also fetch the trainer's material
                $material = DB::table('training_materials')->where('trainer_id', $review->user_id)->first();
                $materialTitle = $material->training_title ?? 'Material not found';
                break;

            case 'mentor':
                $reviewee = DB::table('mentors')->where('id', $review->user_id)->first();
                $revieweeName = $reviewee->name ?? 'N/A';
                break;

            case 'coach':
                $reviewee = DB::table('coaches')->where('id', $review->user_id)->first();
                $revieweeName = $reviewee->name ?? 'N/A';
                break;

            case 'assessor':
                $reviewee = DB::table('assessors')->where('id', $review->user_id)->first();
                $revieweeName = $reviewee->name ?? 'N/A';
                break;
        }

        return view('admin.reviews.view', compact('review', 'revieweeName', 'materialTitle'));
    }



    public function trainingCategory()
    {
        $trainingCategory = TrainingCategory::orderBy('id', 'desc')->get();
        // echo "<pre>"; print_r($reviews); die;
        return view('admin.trainingcategory.index', compact('trainingCategory'));
    }


    public function trainingCategoryEdit($id)
    {
        $trainingCategory = TrainingCategory::find($id);
        // echo "<pre>"; print_r($trainingCategory); die;
        return view('admin.trainingcategory.edit', compact('trainingCategory'));
    }

    public function updatetrainingCategory(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        $category = TrainingCategory::findOrFail($id);
        $category->category = $request->category_name;

        if ($request->hasFile('category_icon')) {
            $file = $request->file('category_icon');
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = 'category_icon_' . time() . '.' . $extension;
            $file->move(public_path('uploads'), $fileNameToStore);
            $category->image_path = asset('uploads/' . $fileNameToStore);
            $category->image_name =  $fileNameToStore;
        }

        $category->save();

        return redirect()->route('admin.training-category')->with('success', 'Category updated successfully.');
    }


    public function createCategory()
    {
        return view('admin.trainingcategory.create');
    }


    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        $iconPath = null;

        if ($request->hasFile('category_icon')) {
            $file = $request->file('category_icon');
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = 'category_icon_' . time() . '.' . $extension;
            $file->move(public_path('uploads'), $fileNameToStore);

            // Store relative path or filename
            $iconPath = asset('uploads/' . $fileNameToStore);
        }

        TrainingCategory::create([
            'category' => $request->category_name,
            'image_path' => $iconPath, // make sure 'icon' column exists in the table
            'image_name' => $fileNameToStore, // make sure 'icon' column exists in the table
        ]);

        return redirect()->route('admin.training-category')->with('success', 'Category added successfully.');
    }



    public function trainingCategoryDestroy($id)
    {
        // Find the category by ID
        $category = TrainingCategory::findOrFail($id);
        // Delete the category
        $category->delete();
        // Redirect back with a success message
        return redirect()->route('admin.training-category')->with('success', 'Category deleted successfully.');
    }



    public function subscriptions()
    {
        return view('admin.subscriptions.index');
    }


    public function showSubscriptions($type)
    {
        // Example logic
        return view('admin.subscriptions.view', ['type' => ucfirst($type)]);
    }


    public function payments()
    {   
        $payments = PaymentHistory::select('payments_history.*', 'jobseekers.name as jobseeker_name', 'jobseekers.email as jobseeker_email','payments_history.id as payment_id')
                    ->join('jobseekers', 'payments_history.jobseeker_id', '=', 'jobseekers.id')
                    ->orderBy('payments_history.created_at', 'desc')
                    ->get();
        // echo "<pre>"; print_r($payments); die;
        return view('admin.payments.index', compact('payments'));
    }

    public function viewPayment($id)
    {
        $payment = PaymentHistory::select('payments_history.*', 'jobseekers.name as jobseeker_name', 'jobseekers.email as jobseeker_email','training_materials.*')
                                ->join('jobseekers', 'payments_history.jobseeker_id', '=', 'jobseekers.id')
                                ->join('training_materials', 'payments_history.material_id', '=', 'training_materials.id')
                                ->where('payments_history.id', $id)
                                ->firstOrFail();

        // Get the jobseeker's details
        $jobseeker = JobSeekers::find($payment->jobseeker_id);

        return view('admin.payments.view', compact('payment', 'jobseeker'));
    }



    public function mentors()
    {
        $mentors = Mentors::all();
        return view('admin.mentors.index', compact('mentors'));
    }

    public function mentorChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'mentor_id' => 'required|exists:mentors,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = Mentors::findOrFail($validated['mentor_id']);
        $oldStatus = $user->status;
        $oldReason = $user->inactive_reason;

        $user->status = $validated['status'];

        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason'];
        } else {
            $user->inactive_reason = null;
        }

        $user->save();

        // Actor performing the change
        $actor = auth()->user();
        // Logging the change
        Log::info('Mentors status updated', [
            'mentors' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? null,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'old_reason' => $oldReason,
                'new_reason' => $user->inactive_reason
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Mentors status updated successfully.',
            'status' => $user->status
        ]);
    }


    public function viewMentor($id)
    {
        $mentor = Mentors::findOrFail($id);
        $educations = $mentor->educations()->orderBy('id', 'desc')->get();
        $experiences = $mentor->experiences()->orderBy('id', 'desc')->get();
        $trainingexperience = $mentor->trainingexperience()->orderBy('id', 'desc')->get();
        $additioninfos = AdditionalInfo::select('*')->where('user_id' , $id)->where('user_type','mentor')->get();
        return view('admin.mentors.view', compact('mentor', 'educations', 'experiences', 'trainingexperience','additioninfos'));
    }


    public function updateMentorStatus(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:mentors,id',
            'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
            'reason' => 'nullable|string|max:1000',
        ]);

        $mentor = Mentors::findOrFail($request->mentor_id);
        $user = auth()->user();
        $previousStatus = $mentor->admin_status;
        $status = $request->status;

        // Role validation
        if ($user->role === 'superadmin' && !Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Invalid status for superadmin');
        } elseif ($user->role === 'admin' && Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Admins cannot perform superadmin actions');
        } elseif (!in_array($user->role, ['admin', 'superadmin'])) {
            Log::warning('Unauthorized mentor status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'mentor_id' => $mentor->id,
                'time' => now(),
            ]);
            return back()->with('error', 'Unauthorized action.');
        }

        // Update status and rejection reason
        $mentor->admin_status = $status;
        $mentor->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
        $mentor->save();

        // Log the update
        Log::info('mentor admin status updated', [
            'mentor' => [
                'id' => $mentor->id,
                'name' => $mentor->name,
                'email' => $mentor->email,
                'previous_status' => $previousStatus,
                'new_status' => $status,
                'rejection_reason' => $mentor->rejection_reason,
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'time' => now(),
        ]);

        // Send email if rejected
        if (Str::endsWith($status, 'rejected') && $request->filled('reason') && $mentor->email) {
            Mail::html('
                <!DOCTYPE html>
                <html>
                <head><meta charset="UTF-8"><title>Application Rejected – Talentrek</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px; color: #333; }
                    .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 30px; }
                    .reason { background-color: #ffe6e6; border-left: 4px solid #dc3545; padding: 10px 15px; margin: 15px 0; }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Application Update – <span style="color:#dc3545;">Rejected</span></h2>
                        </div>
                        <p>Hi <strong>' . e($mentor->name ?? $mentor->email) . '</strong>,</p>
                        <p>We regret to inform you that your application on <strong>Talentrek</strong> has been rejected.</p>
                        <div class="reason"><strong>Reason:</strong> ' . e($request->reason) . '</div>
                        <p>If you believe this was a mistake, contact <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>
                        <p>Thank you,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">© ' . date('Y') . ' Talentrek. All rights reserved.</div>
                </body>
                </html>
            ', function ($message) use ($mentor) {
                $message->to($mentor->email)->subject('Application Rejected – Talentrek');
            });
        }

        return back()->with('success', 'Status updated successfully.');
    }



    public function viewBookingSession($id)
    {
        $bookingSessions = BookingSession::select('jobseeker_saved_booking_session.*', 'mentors.name as mentor_name', 'mentors.email as mentor_email','jobseekers.name as jobseeker_name', 'jobseekers.email as jobseeker_email','booking_slots.start_time', 'booking_slots.end_time','booking_slots.*','jobseeker_saved_booking_session.status as booking_status','jobseeker_saved_booking_session.id as booking_id')
                                ->join('mentors', 'jobseeker_saved_booking_session.user_id', '=', 'mentors.id')
                                ->join('jobseekers', 'jobseeker_saved_booking_session.jobseeker_id', '=', 'jobseekers.id')
                                ->join('booking_slots', 'jobseeker_saved_booking_session.booking_slot_id', '=', 'booking_slots.id')
                                ->where('jobseeker_saved_booking_session.user_id', $id)
                                ->where('jobseeker_saved_booking_session.user_type', 'mentor')
                                ->get();


        // $bookingSessions = BookingSession::select('jobseeker_saved_booking_session.*')
        //                                         ->where('jobseeker_saved_booking_session.user_id', $id)
        //                                         ->where('jobseeker_saved_booking_session.user_type', 'mentor')
        //                                         ->get();
        // echo "<pre>"; print_r($bookingSessions); die;
        return view('admin.mentors.booking-session', compact('bookingSessions'));
    }



    public function coach()
    {
        $coaches = Coach::all();
        return view('admin.coach.index', compact('coaches'));
    }

    public function coachChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:coaches,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = Coach::findOrFail($validated['coach_id']);
        $oldStatus = $user->status;
        $oldReason = $user->inactive_reason;

        $user->status = $validated['status'];

        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason'];
        } else {
            $user->inactive_reason = null;
        }

        $user->save();

        // Actor performing the change
        $actor = auth()->user();
        // Logging the change
        Log::info('Coach status updated', [
            'coach' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? null,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'old_reason' => $oldReason,
                'new_reason' => $user->inactive_reason
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Coach status updated successfully.',
            'status' => $user->status
        ]);
    }

    public function viewCoach($id)
    {
        $coach = Coach::findOrFail($id);
        $educations = $coach->educations()->orderBy('id', 'desc')->get();
        $experiences = $coach->experiences()->orderBy('id', 'desc')->get();
        $trainingexperience = $coach->trainingexperience()->orderBy('id', 'desc')->get();
        $additioninfos = AdditionalInfo::select('*')->where('user_id' , $id)->where('user_type','coach')->get();
        return view('admin.coach.view', compact('coach', 'educations', 'experiences', 'trainingexperience','additioninfos'));
    }


    public function updateCoachStatus(Request $request)
    {
        $request->validate([
            'coach_id' => 'required|exists:coaches,id',
            'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
            'reason' => 'nullable|string|max:1000',
        ]);

        $coach = Coach::findOrFail($request->coach_id);
        $user = auth()->user();
        $previousStatus = $coach->admin_status;
        $status = $request->status;

        // Role validation
        if ($user->role === 'superadmin' && !Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Invalid status for superadmin');
        } elseif ($user->role === 'admin' && Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Admins cannot perform superadmin actions');
        } elseif (!in_array($user->role, ['admin', 'superadmin'])) {
            Log::warning('Unauthorized mentor status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'coach_id' => $coach->id,
                'time' => now(),
            ]);
            return back()->with('error', 'Unauthorized action.');
        }

        // Update status and rejection reason
        $coach->admin_status = $status;
        $coach->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
        $coach->save();

        // Log the update
        Log::info('Coach admin status updated', [
            'coach' => [
                'id' => $coach->id,
                'name' => $coach->name,
                'email' => $coach->email,
                'previous_status' => $previousStatus,
                'new_status' => $status,
                'rejection_reason' => $coach->rejection_reason,
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'time' => now(),
        ]);

        // Send email if rejected
        if (Str::endsWith($status, 'rejected') && $request->filled('reason') && $coach->email) {
            Mail::html('
                <!DOCTYPE html>
                <html>
                <head><meta charset="UTF-8"><title>Application Rejected – Talentrek</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px; color: #333; }
                    .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 30px; }
                    .reason { background-color: #ffe6e6; border-left: 4px solid #dc3545; padding: 10px 15px; margin: 15px 0; }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Application Update – <span style="color:#dc3545;">Rejected</span></h2>
                        </div>
                        <p>Hi <strong>' . e($coach->name ?? $coach->email) . '</strong>,</p>
                        <p>We regret to inform you that your application on <strong>Talentrek</strong> has been rejected.</p>
                        <div class="reason"><strong>Reason:</strong> ' . e($request->reason) . '</div>
                        <p>If you believe this was a mistake, contact <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>
                        <p>Thank you,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">© ' . date('Y') . ' Talentrek. All rights reserved.</div>
                </body>
                </html>
            ', function ($message) use ($coach) {
                $message->to($coach->email)->subject('Application Rejected – Talentrek');
            });
        }

        return back()->with('success', 'Status updated successfully.');
    }




    public function viewCoachBookingSession($id)
    {
        $bookingSessions = BookingSession::select('jobseeker_saved_booking_session.*', 'coaches.name as coach_name', 'coaches.email as coach_email','jobseekers.name as jobseeker_name', 'jobseekers.email as jobseeker_email','booking_slots.start_time', 'booking_slots.end_time','booking_slots.*','jobseeker_saved_booking_session.status as booking_status','jobseeker_saved_booking_session.id as booking_id')
                                ->join('coaches', 'jobseeker_saved_booking_session.user_id', '=', 'coaches.id')
                                ->join('jobseekers', 'jobseeker_saved_booking_session.jobseeker_id', '=', 'jobseekers.id')
                                ->join('booking_slots', 'jobseeker_saved_booking_session.booking_slot_id', '=', 'booking_slots.id')
                                ->where('jobseeker_saved_booking_session.user_id', $id)
                                ->where('jobseeker_saved_booking_session.user_type', 'coach')
                                ->get();
        return view('admin.coach.booking-session', compact('bookingSessions'));
    }






    public function assessors()
    {
        $assessors = Assessors::all();
        return view('admin.assessors.index', compact('assessors'));
    }

    public function assessorChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = Assessors::findOrFail($validated['assessor_id']);
        $oldStatus = $user->status;
        $oldReason = $user->inactive_reason;

        $user->status = $validated['status'];

        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason'];
        } else {
            $user->inactive_reason = null;
        }

        $user->save();

        // Actor performing the change
        $actor = auth()->user();
        // Logging the change
        Log::info('Assessor status updated', [
            'assessor' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? null,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'old_reason' => $oldReason,
                'new_reason' => $user->inactive_reason
            ],
            'changed_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown'
            ],
            'time' => now()
        ]);

        return response()->json([
            'message' => 'Assessor status updated successfully.',
            'status' => $user->status
        ]);
    }

    public function viewAssessor($id)
    {
        $assessor = Assessors::findOrFail($id);
        $educations = $assessor->educations()->orderBy('id', 'desc')->get();
        $experiences = $assessor->experiences()->orderBy('id', 'desc')->get();
        $trainingexperience = $assessor->trainingexperience()->orderBy('id', 'desc')->get();
        $additioninfos = AdditionalInfo::select('*')->where('user_id' , $id)->where('user_type','assessor')->get();
        return view('admin.assessors.view', compact('assessor', 'educations', 'experiences', 'trainingexperience','additioninfos'));
    }


    public function updateAssessorStatus(Request $request)
    {
        $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'status' => 'required|in:approved,rejected,superadmin_approved,superadmin_rejected',
            'reason' => 'nullable|string|max:1000',
        ]);

        $assessor = Assessors::findOrFail($request->assessor_id);
        $user = auth()->user();
        $previousStatus = $assessor->admin_status;
        $status = $request->status;

        // Role validation
        if ($user->role === 'superadmin' && !Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Invalid status for superadmin');
        } elseif ($user->role === 'admin' && Str::startsWith($status, 'superadmin_')) {
            return back()->with('error', 'Admins cannot perform superadmin actions');
        } elseif (!in_array($user->role, ['admin', 'superadmin'])) {
            Log::warning('Unauthorized mentor status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'assessor_id' => $assessor->id,
                'time' => now(),
            ]);
            return back()->with('error', 'Unauthorized action.');
        }

        // Update status and rejection reason
        $assessor->admin_status = $status;
        $assessor->rejection_reason = Str::endsWith($status, 'rejected') ? $request->reason : null;
        $assessor->save();

        // Log the update
        Log::info('assessor admin status updated', [
            'assessor' => [
                'id' => $assessor->id,
                'name' => $assessor->name,
                'email' => $assessor->email,
                'previous_status' => $previousStatus,
                'new_status' => $status,
                'rejection_reason' => $assessor->rejection_reason,
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'time' => now(),
        ]);

        // Send email if rejected
        if (Str::endsWith($status, 'rejected') && $request->filled('reason') && $assessor->email) {
            Mail::html('
                <!DOCTYPE html>
                <html>
                <head><meta charset="UTF-8"><title>Application Rejected – Talentrek</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f6f8fa; padding: 20px; color: #333; }
                    .container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .footer { font-size: 12px; text-align: center; color: #999; margin-top: 30px; }
                    .reason { background-color: #ffe6e6; border-left: 4px solid #dc3545; padding: 10px 15px; margin: 15px 0; }
                </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h2>Application Update – <span style="color:#dc3545;">Rejected</span></h2>
                        </div>
                        <p>Hi <strong>' . e($assessor->name ?? $assessor->email) . '</strong>,</p>
                        <p>We regret to inform you that your application on <strong>Talentrek</strong> has been rejected.</p>
                        <div class="reason"><strong>Reason:</strong> ' . e($request->reason) . '</div>
                        <p>If you believe this was a mistake, contact <a href="mailto:support@talentrek.com">support@talentrek.com</a>.</p>
                        <p>Thank you,<br><strong>The Talentrek Team</strong></p>
                    </div>
                    <div class="footer">© ' . date('Y') . ' Talentrek. All rights reserved.</div>
                </body>
                </html>
            ', function ($message) use ($assessor) {
                $message->to($assessor->email)->subject('Application Rejected – Talentrek');
            });
        }

        return back()->with('success', 'Status updated successfully.');
    }




    public function viewAssessorBookingSession($id)
    {
        $bookingSessions = BookingSession::select('jobseeker_saved_booking_session.*', 'assessors.name as coach_name', 'assessors.email as coach_email','jobseekers.name as jobseeker_name', 'jobseekers.email as jobseeker_email','booking_slots.start_time', 'booking_slots.end_time','booking_slots.*','jobseeker_saved_booking_session.status as booking_status','jobseeker_saved_booking_session.id as booking_id')
                                ->join('assessors', 'jobseeker_saved_booking_session.user_id', '=', 'assessors.id')
                                ->join('jobseekers', 'jobseeker_saved_booking_session.jobseeker_id', '=', 'jobseekers.id')
                                ->join('booking_slots', 'jobseeker_saved_booking_session.booking_slot_id', '=', 'booking_slots.id')
                                ->where('jobseeker_saved_booking_session.user_id', $id)
                                ->where('jobseeker_saved_booking_session.user_type', 'assessor')
                                ->get();
        return view('admin.assessors.booking-session', compact('bookingSessions'));
    }

    public function showActivityLog()
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return view('admin.logs', ['logs' => []]);
        }

        $lines = File::lines($logPath)->toArray();

       $filteredLogs = collect($lines)
        ->filter(function ($line) {
            return Str::contains($line, [
                'Admin login successful',
                'Admin logged out',
                'Jobseekers assigned to admin',
                'Jobseeker status updated',
                'Jobseeker admin status updated',
                'Recruiter status updated',
                'Recruiter admin status updated',
            ]);
        })

        ->map(function ($line) {
            preg_match('/^\[(.*?)\] (\w+)\.(\w+): (.+)$/', $line, $matches);

            if (empty($matches)) {
                return null;
            }

            $timestamp = $matches[1] ?? '';
            $level = strtoupper($matches[2] ?? '');
            $message = $matches[3] ?? '';
            $rawData = $matches[4] ?? '';

            // Split message and data if merged
            if (Str::contains($rawData, '{')) {
                $parts = explode('{', $rawData, 2);
                $messageText = trim($parts[0]);
                $jsonString = '{' . ($parts[1] ?? '');
            } else {
                $messageText = $rawData;
                $jsonString = '{}';
            }

            // Remove slashes and decode
            $cleanedJson = stripslashes($jsonString);
            $decoded = json_decode($cleanedJson, true);

            return [
                'timestamp' => $timestamp,
                'level' => $level,
                'message' => $messageText,
                'data' => $decoded ?? $cleanedJson,
            ];
        })
        ->filter()
        ->reverse()
        ->values();


        return view('admin.logs', ['logs' => $filteredLogs]);
    }




    
}
