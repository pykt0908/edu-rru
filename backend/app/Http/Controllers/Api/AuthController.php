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
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'กรุณากรอกอีเมล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        $throttleKey = Str::transliterate(Str::lower($validated['email']) . '|' . $request->ip());

        // Max 5 attempts per 60 seconds
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'message' => "คุณพยายามเข้าสู่ระบบไม่ถูกต้องหลายครั้งเกินไป เพื่อความปลอดภัย กรุณารออีก {$seconds} วินาทีแล้วลองใหม่",
                'retry_after' => $seconds,
            ], 429);
        }

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Count failed attempt with 60 seconds decay
            RateLimiter::hit($throttleKey, 60);

            $remaining = RateLimiter::remaining($throttleKey, 5);

            return response()->json([
                'message' => $remaining > 0
                    ? "อีเมลหรือรหัสผ่านไม่ถูกต้อง (เหลือโอกาสลองอีก {$remaining} ครั้ง)"
                    : 'อีเมลหรือรหัสผ่านไม่ถูกต้อง คุณพยายามเข้าสู่ระบบเกินกำหนด กรุณารอ 1 นาที',
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
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
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
        return response()->json([
            'user' => [
                'id'    => $request->user()->id,
                'name'  => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ]);
    }
}
