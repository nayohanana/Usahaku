<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;

        // Hapus OTP lama
        PasswordResetOtp::where('email', $email)->delete();

        // Generate OTP 6 digit
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke database
        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_used' => false,
        ]);

        // Kirim email
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'email' => $email], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Kode OTP Reset Password - UsahaKu')
                        ->from(env('MAIL_FROM_ADDRESS'), 'UsahaKu');
            });

            return redirect()->route('password.verify')
                ->with('email', $email)
                ->with('success', 'Kode OTP telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim email. Silahkan coba lagi.');
        }
    }

    public function showVerifyForm()
    {
        $email = session('email');
        if (!$email) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->first();

        if (!$otpRecord) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid.');
        }

        if (!$otpRecord->isValid()) {
            return redirect()->back()->with('error', 'Kode OTP sudah kadaluarsa.');
        }

        // Tandai OTP sudah digunakan
        $otpRecord->update(['is_used' => true]);

        // Simpan email di session untuk reset password
        session(['reset_email' => $request->email]);

        return redirect()->route('password.reset')->with('email', $request->email);
    }

    public function showResetForm()
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Hapus session
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silahkan login.');
    }
}