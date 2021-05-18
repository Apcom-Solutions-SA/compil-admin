<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAdded extends Mailable
{
    use Queueable, SerializesModels;

    public $key;

    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $key, string $url)
    {
        $this->key = $key; 
        $this->url = $url;
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
            'key' => $this->key,
            'url' => $this->url
        ]);
    }
}
