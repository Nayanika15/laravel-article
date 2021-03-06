<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use app\Models\User;

class ResetPasswordMailAdmin extends Mailable
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
        return $this->markdown('mails.user.reset-password-admin')
                ->with('userDetail', $this->userDetail);
    }
}
