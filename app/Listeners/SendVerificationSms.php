<?php

namespace App\Listeners;

use App\Services\SmsService;
use App\Events\UserRegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationSms
{
    /**
     * Create the event listener.
     */ 
     protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEvent $event)
    {
        $user = $event->user;
        $message = "Your verification code is {$user->verification_code}.";
        $this->smsService->sendSms($user->phone, $message);
    }
}
