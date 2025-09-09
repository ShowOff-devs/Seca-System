<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login logic
    public function login(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'))) {
            // Redirect to the dashboard on successful login
            return redirect()->route('dashboard');
        }

        // Redirect back with an error message on failure
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    // Handle logout logic
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session to ensure the user is fully logged out
        $request->session()->invalidate();

        // Regenerate the CSRF token for security purposes
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login');
    }
}
