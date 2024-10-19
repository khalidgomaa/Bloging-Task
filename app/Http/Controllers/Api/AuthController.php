<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\VerifyPhoneRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $result = $this->authService->register($data);

        return response()->json($result, 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
      

        $result = $this->authService->login($data);

        if (!$result) {
            return response()->json(['message' => 'Invalid credentials or unverified account'], 401);
        }

        return response()->json($result);
    }

    public function verify(VerifyPhoneRequest $request)
    {
        $data = $request->validated();

        $isVerified = $this->authService->verifyCode($data['phone'], $data['verification_code']);

        if ($isVerified) {
            return response()->json(['message' => 'Account verified successfully']);
        }

        return response()->json(['message' => 'Invalid verification code'], 400);
    }


    public function logout(Request $request)
{
    // Revoke the token that was used to authenticate the current request
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Successfully logged out']);
}
}
