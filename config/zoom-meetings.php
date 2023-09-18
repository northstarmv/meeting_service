<?php

return [
    'account_id' => env('ZOOM_ACCOUNT_ID'),
    'client_id' => env('ZOOM_CLIENT_ID'),
    'client_secret' => env('ZOOM_CLIENT_SECRET'),
    'base_url' => 'https://api.zoom.us/v2/',
    'token_url' => 'https://zoom.us/oauth/token',
    'revoke_url' => 'https://zoom.us/oauth/revoke',
];
