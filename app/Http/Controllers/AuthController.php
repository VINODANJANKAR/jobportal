<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $mobile = $request->input('mobile');

        // Save OTP to the database (in a real app, send via SMS)
        DB::table('otps')->updateOrInsert(
            ['mobile' => $mobile],
            ['otp' => $otp, 'created_at' => now()]
        );

        // Simulate sending OTP (replace with actual SMS service)
        return response()->json(['message' => "OTP sent to $mobile", 'otp' => $otp]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $mobile = $request->input('mobile');
        $otp = $request->input('otp');

        $record = DB::table('otps')->where('mobile', $mobile)->first();

        if ($record && $record->otp == $otp && now()->diffInMinutes($record->created_at) <= 15) {
            // OTP is valid; create or find user
            $user = User::firstOrCreate(['mobile' => $mobile]);

            // Generate an API token for the user
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token]);
        }

        return response()->json(['error' => 'Invalid OTP or expired'], 401);
    }
}

