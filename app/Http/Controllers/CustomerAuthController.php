<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function login()
    {
        return view('web.customer.login', [
            'title' => 'Login'
        ]);
    }

    public function register()
    {
        return view('web.customer.register', [
            'title' => 'Register'
        ]);
    }

    public function store_register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            return redirect()->back()
                ->with('errorMessage', 'Registrasi gagal. Silakan periksa kembali data Anda.')
                ->withErrors($validasi)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        return redirect()->route('customer.login')->with('successMessage', 'Registrasi Berhasil. Silakan login.');
    }

    public function store_login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validasi = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validasi->fails()) {
            return back()->withErrors($validasi)->withInput()->with('errorMessage', 'Email dan password wajib diisi.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('successMessage', 'Selamat datang Admin!');
            }

            return redirect()->route('home')->with('successMessage', 'Login Berhasil!');
        }

        return back()->withInput()->with('errorMessage', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')->with('successMessage', 'Anda telah berhasil logout.');
    }
}
