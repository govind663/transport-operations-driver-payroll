<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Postmark
    |--------------------------------------------------------------------------
    |
    | Postmark API configuration.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Resend
    |--------------------------------------------------------------------------
    |
    | Resend API configuration.
    |
    */

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Amazon SES
    |--------------------------------------------------------------------------
    |
    | Amazon Simple Email Service configuration.
    |
    */

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Slack
    |--------------------------------------------------------------------------
    |
    | Slack notification configuration.
    |
    */

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | OpenAI AI Chatbot
    |--------------------------------------------------------------------------
    |
    | OpenAI API configuration.
    |
    */

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
    ],


    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business Cloud API
    |--------------------------------------------------------------------------
    |
    | Mastermind Travels WhatsApp Communication System
    |
    | Supported modes:
    |
    | 1. local:
    |    - Personal WhatsApp chat link (wa.me).
    |    - No automatic message dispatch.
    |    - No Cloud API dependency for personal chat flow.
    |
    | 2. live:
    |    - Official Meta WhatsApp Cloud API.
    |    - Automatic template message dispatch.
    |    - PDF document media support.
    |
    | IMPORTANT:
    | Live API failure must never trigger Personal WhatsApp fallback.
    |
    */

    'whatsapp' => [

        /*
        |--------------------------------------------------------------------------
        | Application Mode
        |--------------------------------------------------------------------------
        |
        | local = Personal WhatsApp
        | live  = WhatsApp Cloud API
        |
        */

        'mode' => env('WHATSAPP_MODE', 'local'),


        /*
        |--------------------------------------------------------------------------
        | WhatsApp API Channel
        |--------------------------------------------------------------------------
        |
        | Local mode uses personal_chat.
        | Live mode uses cloud_api.
        |
        */

        'channel' => env(
            'WHATSAPP_MODE',
            'local'
        ) === 'live'
            ? 'cloud_api'
            : 'personal_chat',


        /*
        |--------------------------------------------------------------------------
        | Meta Graph API Base URL
        |--------------------------------------------------------------------------
        */

        'base_url' => env(
            'WHATSAPP_API_BASE_URL',
            'https://graph.facebook.com'
        ),


        /*
        |--------------------------------------------------------------------------
        | Meta Graph API Version
        |--------------------------------------------------------------------------
        */

        'graph_api_version' => env(
            'WHATSAPP_GRAPH_API_VERSION',
            'v27.0'
        ),


        /*
        |--------------------------------------------------------------------------
        | WhatsApp Business Phone Number ID
        |--------------------------------------------------------------------------
        |
        | Required for Live Cloud API mode.
        |
        */

        'phone_number_id' => env(
            'WHATSAPP_PHONE_NUMBER_ID'
        ),


        /*
        |--------------------------------------------------------------------------
        | Meta Permanent Access Token
        |--------------------------------------------------------------------------
        |
        | Required for Live Cloud API mode.
        |
        */

        'access_token' => env(
            'WHATSAPP_ACCESS_TOKEN'
        ),

                /*
        |--------------------------------------------------------------------------
        | API Request Timeout
        |--------------------------------------------------------------------------
        |
        | Maximum time allowed for an API request.
        | Value is in seconds.
        |
        */

        'timeout' => (int) env(
            'WHATSAPP_API_TIMEOUT',
            30
        ),


        /*
        |--------------------------------------------------------------------------
        | API Connection Timeout
        |--------------------------------------------------------------------------
        |
        | Maximum time allowed to establish API connection.
        | Value is in seconds.
        |
        */

        'connect_timeout' => (int) env(
            'WHATSAPP_API_CONNECT_TIMEOUT',
            10
        ),


        /*
        |--------------------------------------------------------------------------
        | Maximum Media Upload Size
        |--------------------------------------------------------------------------
        |
        | Maximum allowed document upload size.
        | Value is in MB.
        |
        */

        'max_media_size_mb' => (int) env(
            'WHATSAPP_MAX_MEDIA_SIZE_MB',
            100
        ),


        /*
        |--------------------------------------------------------------------------
        | WhatsApp Log Configuration
        |--------------------------------------------------------------------------
        |
        | Central database table for WhatsApp message logs.
        |
        */

        'log_table' => env(
            'WHATSAPP_LOG_TABLE',
            'whatsapp_message_logs'
        ),


        /*
        |--------------------------------------------------------------------------
        | Document Configuration
        |--------------------------------------------------------------------------
        |
        | Private storage is used for driver documents.
        | Documents should not be stored in public storage.
        |
        */

        'documents' => [

            'disk' => env(
                'WHATSAPP_DOCUMENT_DISK',
                'local'
            ),

            'directory' => env(
                'WHATSAPP_DOCUMENT_DIRECTORY',
                'driver-documents'
            ),

        ],


        /*
        |--------------------------------------------------------------------------
        | Local Personal WhatsApp Configuration
        |--------------------------------------------------------------------------
        |
        | Used only when WHATSAPP_MODE=local.
        |
        | Personal WhatsApp chat link generation does not mean that
        | a message has been sent, delivered or read.
        |
        */

        'personal' => [

            'enabled' => env(
                'WHATSAPP_PERSONAL_ENABLED',
                true
            ),

            'base_url' => 'https://wa.me',

        ],


        /*
        |--------------------------------------------------------------------------
        | Cloud API Configuration
        |--------------------------------------------------------------------------
        |
        | Used only when WHATSAPP_MODE=live.
        |
        */

        'cloud_api' => [

            'enabled' => env(
                'WHATSAPP_CLOUD_API_ENABLED',
                true
            ),

            'messaging_product' => 'whatsapp',

            'recipient_type' => 'individual',

        ],

                /*
        |--------------------------------------------------------------------------
        | WhatsApp Template Configuration
        |--------------------------------------------------------------------------
        |
        | Template messages are managed centrally through
        | WhatsAppTemplateService.
        |
        | Actual template names and language settings should remain
        | managed by that service.
        |
        */

        'templates' => [

            'default_language' => env(
                'WHATSAPP_TEMPLATE_LANGUAGE',
                'en'
            ),

        ],


        /*
        |--------------------------------------------------------------------------
        | Webhook Configuration
        |--------------------------------------------------------------------------
        |
        | Reserved configuration for future delivery-status webhook
        | integration.
        |
        | Do not enable webhook verification until the corresponding
        | webhook controller and route are implemented.
        |
        */

        'webhook' => [

            'verify_token' => env(
                'WHATSAPP_WEBHOOK_VERIFY_TOKEN'
            ),

            'app_secret' => env(
                'WHATSAPP_APP_SECRET'
            ),

        ],

    ],

];
