<?php

namespace App\Mail;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Ticket $ticket)
    {
    }

    public function build(): self
    {
        $pdf = Pdf::loadView('receipts.ticket', ['ticket' => $this->ticket]);

        return $this
            ->subject('Votre commande est prête — Ticket n°' . $this->ticket->id)
            ->view('emails.ticket-ready')
            ->attachData(
                $pdf->output(),
                'recu-ticket-' . $this->ticket->id . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
