<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $token;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token, $url)
    {
        $this->email = $email;
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['email'] = $this->email;
        $data['token'] = $this->token;
        $data['url'] = $this->url;

        return $this->from(env('MAIL_FROM_ADDRESS'), $data['application']->name)
                    ->view('emails.users.forgot-password', $data)
                    ->subject('Lupa Kata Sandi');
    }
}
