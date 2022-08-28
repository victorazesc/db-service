<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contact extends Mailable
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
        $this->to('fabiola@db-assuntosregulatorios.com','Fabiola' );

        return $this->markdown('mail.contact', [ 'user' => $this->request]);
    }
}
