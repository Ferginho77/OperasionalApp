<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
       $request->validate([
                                    'username' => ['required', 'regex:/^[a-zA-Z0-9_]{4,20}$/'],
                                    'password' => ['required', 'min:3', 'max:20', 'regex:/^[a-zA-Z0-9_]{3,20}$/'],
                                ]);
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect('/dashboard');
            } elseif ($user->role === 'owner') {
                return redirect('/owner');
            } else {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['login' => 'Role tidak dikenali.'])
                    ->withInput();
            }
        }

        return redirect()->back()
            ->withErrors(['login' => 'Username atau Password salah.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
