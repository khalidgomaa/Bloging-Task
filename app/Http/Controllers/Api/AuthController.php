<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\VerifyPhoneRequest;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            
            $result = $this->authService->register($data);

            return response()->json($result, 201);
        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->authService->login($data);
            return response()->json($result, 201);

        }  catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    

    public function verify(VerifyPhoneRequest $request)
    {
        try {
            $data = $request->validated();
            $isVerified = $this->authService->verifyCode($data['phone'], $data['verification_code']);

            if ($isVerified) {
                return response()->json(['message' => 'Account verified successfully']);
            }

            return response()->json(['message' => 'Invalid verification code'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Auth token not found. Log in now!',
            ], 401);
        }
        // dd($request->user());
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Successfully logged out'],200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
