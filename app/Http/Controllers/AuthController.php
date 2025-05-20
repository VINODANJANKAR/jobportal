<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Otps;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|digits:10',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $otp = rand(100000, 999999); // Generate a 6-digit OTP
            $mobile = $request->input('mobile');

            $msg = "Your Tirumala Job App login one time password is ". $otp .". Use this code within 10 minutes for secure access. Do not share with anyone. TIRUMALA IASPL";
                    
            $apiResponse = $this->curl_call_api($mobile, $msg);
            
            $data = [];
            $data['mobile'] = $mobile;
            $data['otp'] = $otp;
            
            // Save OTP to the database (in a real app, send via SMS)
            if($apiResponse != NULL ){
                Otps::create($data);

                // Simulate sending OTP (replace with actual SMS service)
                return response()->json(['message' => "OTP sent to $mobile", 'otp' => $otp]);
            }
        }catch (Exception $e) {
            \Log::error('Error creating profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while generating otp.'], 500);
        }
        
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
        
        $record = DB::table('otps')->where('mobile', $mobile)->orderBy('created_at','DESC')->first();
        // dd(now(), $record);
        if ($record && now()->diffInMinutes($record->created_at) <= 10) {

            // OTP is valid; create or find user
            $user = User::firstOrCreate(['mobile' => $mobile]);

            // Generate an API token for the user
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token], 200);
        }

        return response()->json(['error' => 'Invalid OTP or expired'], 401);
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // Use 'confirmed' to ensure the new password matches
        ]);

        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);
        
        $user = User::where('mobile', $request->mobile)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            // Generate Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;
            // dd('in', $token);

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the user's token if you're using Laravel Passport/Sanctum
            $user = Auth::user();
            $user->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to log out.'], 500);
        }
    }

    public function curl_call_api($mobile, $msg){

        $mobile = urlencode('91'.$mobile);
        $msg = urlencode($msg);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://login.wishbysms.com/api/sendhttp.php?authkey=201148ATXmoDrFpOmT5def5ff3&mobiles=". $mobile ."&message=". $msg ."&sender=TIASPL&route=4&country=91&DLT_TE_ID=1707174712096037190",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}

