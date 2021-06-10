<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $password)
    {
        $this->password = $password; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Forgot Password')
            ->markdown('emails.user.forgot_password',[
            'password' => $this->password,
        ]);
    }
}
