<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAdded extends Mailable
{
    use Queueable, SerializesModels;

    protected $password;
    public $public_id; 
    public $url;
    public $email; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $password, string $public_id, string $url, string $email)
    {
        $this->password = $password; 
        $this->public_id = $public_id; 
        $this->url = $url;
        $this->email = $email; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New user')
            ->markdown('emails.user.added',[
            'password' => $this->password,
            'public_id' => $this->public_id,
            'url' => $this->url, 
            'email' => $this->email,
        ]);
    }
}
