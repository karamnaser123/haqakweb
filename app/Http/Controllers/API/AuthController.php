<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('profile', 'public');
        }else{
            $imagePath = null;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imagePath,
            'password' => Hash::make($request->password),
            'code' => 0,
        ]);


        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->save();

        // إنشاء رقم فريد للمستخدم (ضمان عدم التكرار)
        $uniqueCode = User::generateUniqueCode($user->id);

        // إنشاء QR code للمستخدم (يحتوي على الرقم الفريد فقط)
        $qrCodeContent = $uniqueCode;

        // إنشاء QR code وحفظه كصورة باستخدام Storage
        $qrCodeFileName = 'qr_codes/user_' . $user->id . '_' . time() . '.png';

        // إنشاء QR code باستخدام EndroidQrCode
        $qrCode = new EndroidQrCode($qrCodeContent);
        $qrCode->setSize(300);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // حفظ QR code باستخدام Storage
        Storage::disk('public')->put($qrCodeFileName, $result->getString());

        // تحديث المستخدم بالرقم الفريد ومسار QR code
        $user->update([
            'code' => $uniqueCode,
            'qr_code' => $qrCodeFileName
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
            'qr_code_url' => asset('storage/' . $qrCodeFileName),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'credentials' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->credentials)->orWhere('phone', $request->credentials)->first();
        // if(!$user){
        //     return response()->json([
        //         'message' => 'User not found',
        //     ], 404);
        // }

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Invalid credentials or password',
            ], 401);
        }

        // if(!$user->email_verified_at){
        //     return response()->json([
        //         'message' => 'Email not verified',
        //     ], 403);
        // }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            // 'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'message' => 'User me successfully',
            'user' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);
        $user = $request->user();
        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'message' => 'Invalid current password',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'User password updated successfully',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'age' => 'required|integer|min:18',
            'gender' => 'required|string|in:male,female',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->age = $request->age;
        $user->gender = $request->gender;
        if($request->hasFile('image')){
            if($user->image) {
                Storage::delete($user->image);
            }
            $imagePath = $request->file('image')->store('profile', 'public');
            $user->image = $imagePath;
        }
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if($user->email_verified_at){
            return response()->json([
                'message' => 'Email already verified',
            ], 400);
        }

        if($user->otp != $request->otp){
            return response()->json([
                'message' => 'Invalid otp',
            ], 400);
        }

        $user->email_verified_at = now();
        $user->otp = null;
        $user->save();
        return response()->json([
            'message' => 'Email verified successfully',
        ]);
    }

    public function resendOtp(Request $request)
    {


        $user = $request->user();
        if($user->email_verified_at){
            return response()->json([
                'message' => 'Email already verified',
            ], 400);
        }


        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->save();
        return response()->json([
            'message' => 'Otp sent successfully',
        ]);
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->save();
        return response()->json([
            'message' => 'Password reset token sent successfully',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'otp' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if($user->otp != $request->otp){
            return response()->json([
                'message' => 'Invalid otp',
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->save();
        return response()->json([
            'message' => 'Password reset successfully',
        ]);

    }

}
