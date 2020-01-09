<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class RegistrationMailUser extends Mailable
{
    use Queueable, SerializesModels;
    public $userDetail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $userDetail)
    {
        $this->userDetail = $userDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.registration.new-registration-user')
            ->with('userDetail', $this->userDetail);
    }
}
