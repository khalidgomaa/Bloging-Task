<?php
namespace App\Services;

use App\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserAlreadyExistsException;
use App\Repositories\User\UserRepositoryInterface;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {

        $existingUser = $this->userRepository->findByPhone($data['phone']);

        if ($existingUser) {
            throw new \Exception(message: 'this account is already found');

        }
        else{
     
            $user = $this->userRepository->register($data);
            // event(new UserRegisteredEvent($user));

            $token =$user->createToken(name: 'auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token
            ], 201);
    
    
    }
}

public function login(array $data)
{
   
    $user = $this->userRepository->findByPhone($data['phone']);
    
    if (!$user || !Hash::check($data['password'], $user->password)) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Invalid credentials or unverified account',
        ], 401);
    }

    if (!$user->is_verified) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Please verify your phone number to log in.',
        ], 403); 
    }
    $token =$user->createToken(name: 'auth_token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'Logged in successfully!',
        'token' => $token
    ], 200);
}


    public function verifyCode($phone, $verificationCode)
    {
        try {
            $user = $this->userRepository->findByPhone($phone);
            if ($user && $user->verification_code == $verificationCode) {
                $this->userRepository->update($user, [
                    'is_verified' => true,
                    'verification_code' => null,
                ]);

                return true;
            }

            throw new \Exception('Invalid verification code');
        } catch (\Exception $e) {
            // \Log::error('Verification error: ' . $e->getMessage());
            throw new \Exception('Error occurred during phone verification: ' . $e->getMessage());
        }
    }
}