<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Added Hugging Face configuration inside the main array
    'huggingface' => [
        'api_token' => env('HUGGINGFACE_API_TOKEN'),
        'inference_api_url' => env('HUGGINGFACE_INFERENCE_API_URL', 'https://api-inference.huggingface.co/models/'),
        'image_generation_model' => env('HUGGINGFACE_IMAGE_GEN_MODEL', 'stabilityai/stable-diffusion-xl-base-1.0'), // Example model
        'summarization_model' => env('HUGGINGFACE_SUMMARIZATION_MODEL', 'facebook/bart-large-cnn'), // Example model
        'keyword_extraction_model' => env('HUGGINGFACE_KEYWORD_MODEL', 'ml6team/keyphrase-extraction-kbir-inspec'), // Example model
    ],

]; // Ensure this is the final closing bracket for the return array

