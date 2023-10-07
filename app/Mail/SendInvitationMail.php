<?php

namespace App\Mail;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Group $group;
    public GroupUser $groupUser;
    public $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct($group, $groupUser, $mailData)
    {
        $this->group = $group;
        $this->groupUser = $groupUser;
        $this->mailData = $mailData;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to join '.$this->group->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sendInvitationMail',
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
