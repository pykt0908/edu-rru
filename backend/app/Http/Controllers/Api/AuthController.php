<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * User login & Sanctum token generation with Rate Limiting
     * Accepts username or email
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้งานหรืออีเมล',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        $throttleKey = Str::transliterate(Str::lower($validated['username']) . '|' . $request->ip());

        // Max 5 attempts per 60 seconds
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'message' => "คุณพยายามเข้าสู่ระบบไม่ถูกต้องหลายครั้งเกินไป เพื่อความปลอดภัย กรุณารออีก {$seconds} วินาทีแล้วลองใหม่",
                'retry_after' => $seconds,
            ], 429);
        }

        $loginInput = $validated['username'];
        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Count failed attempt with 60 seconds decay
            RateLimiter::hit($throttleKey, 60);

            $remaining = RateLimiter::remaining($throttleKey, 5);

            return response()->json([
                'message' => $remaining > 0
                    ? "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง (เหลือโอกาสลองอีก {$remaining} ครั้ง)"
                    : 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง คุณพยายามเข้าสู่ระบบเกินกำหนด กรุณารอ 1 นาที',
                'remaining_attempts' => $remaining,
            ], 401);
        }

        // Clear rate limiter upon successful authentication
        RateLimiter::clear($throttleKey);

        // Generate token
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'เข้าสู่ระบบสำเร็จ',
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
            ],
            'token'   => $token,
        ]);
    }

    /**
     * Logout & revoke current token
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'ออกจากระบบเรียบร้อยแล้ว'
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
            ]
        ]);
    }

    /**
     * Update authenticated user profile and password
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'username'                  => 'required|string|max:255|alpha_dash|unique:users,username,' . $user->id,
            'email'                     => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password'          => 'nullable|string',
            'new_password'              => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'             => 'กรุณากรอกชื่อ-นามสกุล',
            'username.required'         => 'กรุณากรอกชื่อผู้ใช้งาน (Username)',
            'username.alpha_dash'       => 'ชื่อผู้ใช้งานต้องประกอบด้วยตัวอักษร ตัวเลข ขีดกลาง (-) หรือขีดล่าง (_) เท่านั้น',
            'username.unique'           => 'ชื่อผู้ใช้งานนี้มีอยู่ในระบบแล้ว กรุณาเลือกชื่ออื่น',
            'email.required'            => 'กรุณากรอกอีเมล',
            'email.email'               => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'              => 'อีเมลนี้มีอยู่ในระบบแล้ว กรุณาใช้อีเมลอื่น',
            'new_password.min'          => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร',
            'new_password.confirmed'    => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
        ]);

        // If new password is provided, current password must be validated
        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password'])) {
                return response()->json([
                    'message' => 'กรุณากรอกรหัสผ่านปัจจุบันเพื่อยืนยันการเปลี่ยนรหัสผ่าน',
                    'errors'  => ['current_password' => ['กรุณากรอกรหัสผ่านปัจจุบัน']],
                ], 422);
            }

            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
                    'errors'  => ['current_password' => ['รหัสผ่านปัจจุบันไม่ถูกต้อง']],
                ], 422);
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->name     = $validated['name'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'];
        $user->save();

        return response()->json([
            'message' => 'บันทึกการตั้งค่าบัญชีเรียบร้อยแล้ว',
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
            ],
        ]);
    }
}
