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

    // Rankolab Default/Managed API Keys & Service Configs
    'huggingface' => [
        'api_token' => env('HUGGINGFACE_API_TOKEN'), // Default Rankolab key
        'inference_api_url' => env('HUGGINGFACE_INFERENCE_API_URL', 'https://api-inference.huggingface.co/models/'),
        'image_generation_model' => env('HUGGINGFACE_IMAGE_GEN_MODEL', 'stabilityai/stable-diffusion-xl-base-1.0'),
        'summarization_model' => env('HUGGINGFACE_SUMMARIZATION_MODEL', 'facebook/bart-large-cnn'),
        'keyword_extraction_model' => env('HUGGINGFACE_KEYWORD_MODEL', 'ml6team/keyphrase-extraction-kbir-inspec'),
    ],

    'google_pagespeed' => [
        'api_key' => env('GOOGLE_PAGESPEED_API_KEY'), // Default Rankolab key
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'), // Default Rankolab key (optional, might not exist)
    ],

    'serpapi' => [
        'api_key' => env('SERPAPI_API_KEY'), // Default Rankolab key (optional, might not exist)
    ],

    'copyscape' => [
        'username' => env('COPYSCAPE_USERNAME'), // Default Rankolab account (optional)
        'api_key' => env('COPYSCAPE_API_KEY'), // Default Rankolab key (optional)
    ],

    'buffer' => [
        'client_id' => env('BUFFER_CLIENT_ID'),
        'client_secret' => env('BUFFER_CLIENT_SECRET'),
        'redirect_uri' => env('BUFFER_REDIRECT_URI'),
        'api_key' => env('BUFFER_API_KEY'), // Default Rankolab key (optional)
    ],

    'moz' => [
        'access_id' => env('MOZ_ACCESS_ID'), // Default Rankolab key (optional)
        'secret_key' => env('MOZ_SECRET_KEY'), // Default Rankolab key (optional)
    ],

    'deepai' => [
        'api_key' => env('DEEPAI_API_KEY'), // Default Rankolab key (optional)
    ],

    'stablediffusion' => [ // Could be HuggingFace or another provider
        'api_key' => env('STABLEDIFFUSION_API_KEY'), // Default Rankolab key (optional)
    ],

    'uptimerobot' => [
        'api_key' => env('UPTIMEROBOT_API_KEY'), // Default Rankolab key (optional)
    ],

    'statuscake' => [
        'api_key' => env('STATUSCAKE_API_KEY'), // Default Rankolab key (optional)
    ],

    'amazon_paapi' => [
        'access_key' => env('AMAZON_PAAPI_ACCESS_KEY'),
        'secret_key' => env('AMAZON_PAAPI_SECRET_KEY'),
        'partner_tag' => env('AMAZON_PAAPI_PARTNER_TAG'),
        'host' => env('AMAZON_PAAPI_HOST', 'webservices.amazon.com'), // Region specific
        'region' => env('AMAZON_PAAPI_REGION', 'us-east-1'), // Region specific
    ],

    'shareasale' => [
        'api_token' => env('SHAREASALE_API_TOKEN'),
        'secret_key' => env('SHAREASALE_SECRET_KEY'),
        'merchant_id' => env('SHAREASALE_MERCHANT_ID'),
    ],

];

