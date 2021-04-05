<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendPasswordResetCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private function updateToken(Request $request)
    {
        $token = Str::random(80);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return $token;
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if ( !auth()->attempt($loginData) ) {
            return response(['message' => 'Invalid Credentials']);
        }
        $userRole = auth()->user()->role;
        if ( $userRole != 'trader' ) {
            return response(['message' => 'Invalid Credentials']);
        }
        Auth::user();
        $accessToken = $this->updateToken($request);

        return response([
            'message' => 'Login Successful',
            #'user' => auth()->user(),
            'access_token' => $accessToken,
            #'role'      => $userRole,
        ]);
    }

    public function initPasswordReset(Request $request)
    {
        $email = "";
        if (!Auth::check()) {
            $this->validate($request, ['email' => 'required|email']);
            $email = $request->email;
            $trader = User::where('email', $email)->first();
            if (!$trader) {
                return response()->json(['message' => 'Sorry, you are not registered on our system'], 200);
            }
            if ($trader->role != 'trader') {
                return response()->json(['message' => 'Sorry, you are not registered on our system'], 200);
            }
        }
        if (Auth::check()) {
            $this->validate($request, ['email' => 'required|email']);
            if ($request->email != auth()->user()->email) {
                return response()->json(['message' => 'The email you entered does not match this account'], 200);
            }
            $email = $request->email;
        }
        $pin = mt_rand(100000, 999999); //Str::random(8);
        $resetToken = Hash::make($pin);
        DB::table('password_resets')->updateOrInsert(['email' => $email], ['token' => $resetToken, 'created_at' => date('Y-m-d H:i:s')]);
        Mail::to($email)->send(new SendPasswordResetCode($pin));
        return response()->json([
            'message' => 'Please check the OTP sent to your email',
            'data' => ['email' => $email]
        ], 200);
        //return response()->json(['pin' => $pin, 'token' => $resetToken]);
    }

    public function confirmOTP(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);
        $token = DB::table('password_resets')->where('email', $request->email)
            ->selectRaw('*, DATE_ADD(created_at, INTERVAL 6 HOUR) AS ttl')
            ->first();
        $curTime = date('Y-m-d H:i:s');
        if (!Hash::check($request->otp, $token->token)){
            return response()->json(['message' => 'Invalid OTP code, pls check your email and try again'], 200);
        }
        if ($curTime > $token->ttl){
            return response()->json(['message' => 'The OTP code you entered has expired'], 200);
        }
        return response()->json([
            'message' => 'Your OTP is verified',
            'data' => [
                'email' => $request->email,
                'verified_otp' => $request->otp,
            ]
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'verified_otp' => 'required|numeric',
            'old_password' => 'nullable',
            'new_password' => 'required|string|min:8',
            'confirm_password' =>'required|string|same:new_password'
        ]);
        if ($request->old_password != "") {
            if ( !(Hash::check($request->old_password, auth()->user()->password)) ) {
                return response()->json(['message' => 'Oops! Old password is not correct.'], 200);
            }
        }
        $sysToken = DB::table('password_resets')->where('email', $request->email)->first();
        if (Hash::check($request->verified_otp, $sysToken->token)){
            DB::beginTransaction();
            $changePass = User::where('email', $request->email)->update(['password' => Hash::make($request->new_password)]);
            $changeToken = DB::table('password_resets')->where('email', $request->email)->update(['token' => Hash::make(Str::random(6))]);
            if ($changePass && $changeToken) {
                DB::commit();
                return response()->json(['message' => 'Password changed successfully.'], 200);
            }
            DB::rollBack();
            return response()->json(['message' => 'Password reset failed, Please try again.'], 200);
        }
        return response()->json(['message' => 'Password Reset failed, Please try again'], 500);
    }

    public function logout(){
        if (Auth::check()) {
            User::where('id', auth()->user()->id)->update(['api_token' => hash('sha256', Str::random(80))]);
            //Auth::user()->token()->revoke();
            return response()->json(['message' =>'Logout successfully'], 200);
        }else{
            return response()->json(['message' =>'Something went wrong'], 500);
        }
    }
}
