<?php
namespace App\Services;

use App\Events\UserRegisteredEvent;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $user = $this->userRepository->register($data);

      
        \Log::info("Verification code for {$user->phone}: {$user->verification_code}");

        event(new UserRegisteredEvent($user));
        $token = $this->userRepository->createToken($user);

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByPhone($data['phone']);

        if (!$user || !Hash::check($data['password'], $user->password) || !$user->is_verified) {
            return null;  // Invalid credentials or unverified account
        }

        $token = $this->userRepository->createToken($user);

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function verifyCode($phone, $verificationCode)
    {
        $user = $this->userRepository->findByPhone($phone);

        if ($user && $user->verification_code == $verificationCode) {
            $this->userRepository->update($user, [
                'is_verified' => true,
                'verification_code' => null,
            ]);

            return true;
        }

        return false;
    }
}
