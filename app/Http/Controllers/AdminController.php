<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Jobseekers;
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
       $jobseekers = Jobseekers::select('jobseekers.*')
                                ->orderBy('id', 'desc')
                                ->get();
        $admins = Admin::select('*')->where('role', 'admin')->get();
        // echo "<pre>"; print_r($jobseekers); die;
        return view('admin.jobseeker.index', compact('jobseekers','admins'));
    }


}
