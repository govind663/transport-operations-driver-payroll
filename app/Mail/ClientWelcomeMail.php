<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Client Instance
     */
    public User $client;

    /**
     * Temporary Password
     */
    public string $password;

    /**
     * Login URL
     */
    public string $loginUrl;

    /**
     * User Role
     */
    public string $role;

    /**
     * Mastermind Travels Website
     */
    public string $websiteUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $client,
        string $password,
        string $role
    ) {
        /*
        |--------------------------------------------------------------------------
        | Client
        |--------------------------------------------------------------------------
        */

        $this->client = $client;


        /*
        |--------------------------------------------------------------------------
        | Temporary Password
        |--------------------------------------------------------------------------
        */

        $this->password = $password;


        /*
        |--------------------------------------------------------------------------
        | Dynamic Role
        |--------------------------------------------------------------------------
        */

        $this->role = $role;


        /*
        |--------------------------------------------------------------------------
        | Mastermind Travels Official Website
        |--------------------------------------------------------------------------
        */

        $this->websiteUrl =
            'https://mastermindtravels.net';


        /*
        |--------------------------------------------------------------------------
        | Mastermind Travels Login URL
        |--------------------------------------------------------------------------
        */

        $this->loginUrl =
            'https://transport.mastermindtravels.net';
    }


    /*
    |--------------------------------------------------------------------------
    | EMAIL ENVELOPE
    |--------------------------------------------------------------------------
    */

    public function envelope(): Envelope
    {
        return new Envelope(
            subject:
                'Welcome to Mastermind Travels | Your Account is Ready',
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EMAIL CONTENT
    |--------------------------------------------------------------------------
    */

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-welcome',

            with: [
                'client' => $this->client,

                'password' => $this->password,

                'loginUrl' => $this->loginUrl,

                'role' => $this->role,

                'websiteUrl' => $this->websiteUrl,
            ],
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATTACHMENTS
    |--------------------------------------------------------------------------
    */

    public function attachments(): array
    {
        return [];
    }
}