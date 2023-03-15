<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    private MailMessage $message;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

        $this->message = (new MailMessage)
        ->subject(__('Verify E-mail Address'))
        ->line(__('Please click the button below to verify your email address.'))
        ->action(__('Verify E-mail Address'), $this->verificationUrl())
        ->line(__('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @return string
     */
    protected function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'frontend.auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->email),
            ]
        );
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
