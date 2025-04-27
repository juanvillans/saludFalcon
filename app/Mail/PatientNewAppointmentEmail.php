<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\Calendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientNewAppointmentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $appointment;
    public $calendar;

    public function __construct(Appointment $appointment, Calendar $calendar)
    {
        $this->appointment = $appointment;
        $this->calendar = $calendar;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {      
        

        return new Envelope(
            subject: 'Cita registrada para: ' . strtoupper($this->calendar->title),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.patientNewAppointment',
            with:[
                'logoUrl' => asset('img/logoBlue.svg')
                ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
