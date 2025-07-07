<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\CMS;
use App\Models\RecruiterCompany;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\AdditionalInfo;
use App\Models\Testimonial;
use App\Models\Trainers;
use App\Models\Language;
use App\Models\TrainingBatch;
use App\Models\TrainerAssessment;
use App\Models\TrainingMaterialsDocument;
use App\Models\CertificateTemplate;
use App\Models\TrainingMaterial;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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
        return view('admin.dashboard');
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
                                        ->where('trainer_assessments.id',$id)
                                        ->get();
        // echo "<pre>"; print_r($assessments); die;
        return view('admin.trainers.assessment.training-assessment', compact('assessments'));
    }

    
    public function viewTrainingAssessmentDetail($trainerId, $assessmentId)
    {
        $assessment = TrainerAssessment::where('trainer_id', $trainerId)
                        ->where('id', $assessmentId)
                        ->with(['questions.options', 'course']) // Load questions with their options and course
                        ->firstOrFail();

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
