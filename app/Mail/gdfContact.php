<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class gdfContact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Email de Contato');
        $this->from($this->request->email, $this->request->name );
        $this->to('fabiola@gdfservicos.com.br','Fabiola' );

        return $this->markdown('mail.contactgdf', [ 'user' => $this->request]);
    }
}
