<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private MailMessage $message;
    private $user;
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;

        $this->message = (new MailMessage)
        ->subject(__('Reset Password Notification'))
        ->line(__('You are receiving this email because we received a password reset request for your account.'))
        ->action(__('Reset Password'), route('frontend.auth.password.reset', ['token' => $this->token, 'email' => $this->user->email]))
        ->line(__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
        ->line(__('If you did not request a password reset, no further action is required.'));
    }


    /**
     * Build the message.
     *
     */
    public function build()
    {
        return  $this->markdown('vendor.notifications.email', $this->message->data());
    }
}
