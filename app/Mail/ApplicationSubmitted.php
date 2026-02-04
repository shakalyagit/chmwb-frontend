<?php

namespace App\Mail;

use App\Models\ApplicationHead;
use App\Models\ApplicationMedia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $applicationHead;

    /**
     * Create a new message instance.
     */
    public function __construct(ApplicationHead $applicationHead)
    {
        $this->applicationHead = $applicationHead;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Submitted Successfully - ' . $this->applicationHead->reference_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure we use the related model instance properly. For a HasOne relation
        // access the relation property (`details`) instead of calling `first()` on the model,
        // which would trigger a new query and return the very first row in the table.
        return new Content(
            view: 'emails.application-submitted',
            with: [
                'applicationHead' => $this->applicationHead,
                'detail' => $this->applicationHead->details,
                'reasons' => $this->applicationHead->reasons,
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
        $profile_pic = ApplicationMedia::where('application_head_id', $this->applicationHead->id)->where('document_type', 'photo')->first();
        // Generate PDF
        $pdf = Pdf::loadView('pdf.application-form', [
            'applicationHead' => $this->applicationHead,
            'detail' => $this->applicationHead->details,
            'reasons' => $this->applicationHead->reasons,
            'profile_pic' => empty($profile_pic)?'':storage_path('app/public/' . $profile_pic->url),
        ]);

        $pdfContent = $pdf->output();
        $filename = 'Application_' . $this->applicationHead->reference_id . '.pdf';

        return [
            Attachment::fromData(fn () => $pdfContent, $filename)
                ->withMime('application/pdf'),
        ];
    }
}
