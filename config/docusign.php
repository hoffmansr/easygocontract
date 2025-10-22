<?php

return [
    'base_uri' => env('DOCUSIGN_BASE_URI', 'https://demo.docusign.net/restapi'),
    'account_id' => env('DOCUSIGN_ACCOUNT_ID'),
    'client_id' => env('DOCUSIGN_CLIENT_ID'),
    'client_secret' => env('DOCUSIGN_CLIENT_SECRET'),
    'redirect_uri' => env('DOCUSIGN_REDIRECT_URI', 'http://localhost:8000/docusign/callback'),
];
