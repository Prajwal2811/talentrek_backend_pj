<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\AdditionalInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            if ($jobseeker && is_null($jobseeker->assigned_admin)) {
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

        return redirect()->back()->with('success', 'Unassigned jobseekers have been successfully assigned to the admin.');
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
        ]);

        $admin = Admin::findOrFail($id);

        // Capture old data before update
        $oldData = $admin->only(['name', 'email', 'notes']);

        // Update admin data
        $admin->name = $request->input('full_name');
        $admin->email = $request->input('email');
        $admin->notes = $request->input('notes');
        $admin->save();

        // Get actor (user who made the update)
        $actor = auth()->user();

        // Log the update action
        Log::info('Admin profile updated', [
            'target_admin' => [
                'id' => $admin->id,
                'old_data' => $oldData,
                'new_data' => $admin->only(['name', 'email', 'notes']),
            ],
            'updated_by' => [
                'id' => $actor?->id ?? null,
                'name' => $actor?->name ?? 'System',
                'email' => $actor?->email ?? 'system',
                'role' => $actor?->role ?? 'unknown',
            ],
            'time' => now()
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
            'status' => 'required|in:approved,rejected',
        ]);

        $jobseeker = Jobseekers::findOrFail($request->jobseeker_id);
        $user = auth()->user();
        $previousStatus = $jobseeker->admin_status;

        if ($user->role === 'superadmin') {
            $jobseeker->admin_status = 'superadmin_' . $request->status;
        } elseif ($user->role === 'admin') {
            $jobseeker->admin_status = $request->status;
        } else {
            Log::warning('Unauthorized jobseeker status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'jobseeker_id' => $jobseeker->id,
                'time' => now()
            ]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $jobseeker->save();

        // ✅ Log the approved/rejected status change
        Log::info('Jobseeker admin status updated', [
            'jobseeker' => [
                'id' => $jobseeker->id,
                'name' => $jobseeker->name,
                'email' => $jobseeker->email ?? null,
                'previous_status' => $previousStatus,
                'new_status' => $jobseeker->admin_status
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            
            'time' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }



    public function recruiters()
    {   
        $recruiters = Auth::guard('recruiter')->user();
        // If the user is a superadmin, show all jobseekers
       
        $recruiters = Recruiters::select('recruiters.*','recruiters_company.*','recruiters.id as recruiter_id')->join('recruiters_company','recruiters.id','=','recruiters_company.recruiter_id')
                                ->orderBy('recruiters.id', 'desc')
                                ->get();
       
        return view('admin.recruiter.index', compact('recruiters'));
    }


    public function recruiterChangeStatus(Request $request)
    {
        $validated = $request->validate([
            'recruiter_id' => 'required|exists:recruiters,id',
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:1000'
        ]);

        $user = Recruiters::findOrFail($validated['recruiter_id']);
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
        // print_r($additioninfos); die;    
        return view('admin.recruiter.view', compact('recruiter', 'company', 'additioninfos'));
    }



    public function updateRecruiterStatus(Request $request)
    {
        $request->validate([
            'recruiter_id' => 'required|exists:recruiters,id',
            'status' => 'required|in:approved,rejected',
        ]);

        $recruiter = Recruiters::findOrFail($request->recruiter_id);
        $user = auth()->user();
        $previousStatus = $recruiter->admin_status;

        if ($user->role === 'superadmin') {
            $recruiter->admin_status = 'superadmin_' . $request->status;
        } elseif ($user->role === 'admin') {
            $recruiter->admin_status = $request->status;
        } else {
            Log::warning('Unauthorized recruiter status update attempt', [
                'attempted_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'jobseeker_id' => $recruiter->id,
                'time' => now()
            ]);

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $recruiter->save();

        // ✅ Log the approved/rejected status change
        Log::info('Recruiter admin status updated', [
            'recruiter' => [
                'id' => $recruiter->id,
                'name' => $recruiter->name,
                'email' => $recruiter->email ?? null,
                'previous_status' => $previousStatus,
                'new_status' => $recruiter->admin_status
            ],
            'updated_by' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            
            'time' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
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
