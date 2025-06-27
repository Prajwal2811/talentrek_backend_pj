<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Jobseekers;
use App\Models\AdditionalInfo;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
   public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => "active"], $request->get('remember'))) {
            return redirect()->route('admin.dashboard');
        } else {
            session()->flash('error', 'Either Email/Password is incorrect');
            return back()->withInput($request->only('email'));
        }
    }


    public function signOut()
    {
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

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create new Admin
        $admin = new Admin();
        $admin->name = $request->input('full_name');
        $admin->email = $request->input('email');
        $admin->password = Hash::make($request->input('password'));
        $admin->pass = $request->input('password');
        $admin->notes = $request->input('notes');
        $admin->role = 'admin';
        $admin->status = 'active'; // Default status
        $admin->save();

        // Redirect with success message
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
        // Delete the admin
        $admin->delete();
        // Redirect back with a success message
        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }


    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'status' => 'required|in:active,inactive',
        ]);
        $user = Admin::find($validated['admin_id']);
        $user->status = $validated['status'];
        $user->save();
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
        $jobseekerIds = explode(',', $validated['jobseeker_ids']); // Convert comma-separated string to array

        // Assign the admin to each selected jobseeker
        foreach ($jobseekerIds as $jobseekerId) {
            $jobseeker = Jobseekers::find($jobseekerId);
            if ($jobseeker) {
                $jobseeker->assigned_admin = $adminId; // assuming jobseeker has admin_id column
                $jobseeker->save();
            }
        }
        return redirect()->back()->with('success', 'Selected jobseekers have been assigned to the admin.');
    }
    


    public function unassign(Request $request)
    {
        $jobseeker = Jobseekers::find($request->id);

        if ($jobseeker) {
            $jobseeker->assigned_admin = null;
            $jobseeker->save();

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
        $admin->name = $request->input('full_name');
        $admin->email = $request->input('email');
        $admin->notes = $request->input('notes');
        $admin->save();

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
            'reason' => 'nullable|string|max:1000' // optional, only needed if inactive
        ]);

        $user = Jobseekers::find($validated['jobseeker_id']);
        $user->status = $validated['status'];

        // Store reason only if status is inactive
        if ($validated['status'] === 'inactive' && isset($validated['reason'])) {
            $user->inactive_reason = $validated['reason']; // Ensure this DB column exists
        } else {
            $user->inactive_reason = null; // clear previous reason if reactivated
        }

        $user->save();

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

}
