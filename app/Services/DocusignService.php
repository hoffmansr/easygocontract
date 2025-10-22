<?php

namespace App\Services;

use DocuSign\eSign\Client\ApiClient;

class DocusignService
{
    private $client;

    public function __construct()
    {
        $config = new \DocuSign\eSign\Configuration();
        $config->setHost(config('docusign.base_uri'));
        $this->client = new ApiClient($config);
    }

    public function getAuthUrl()
    {
        return "https://account-d.docusign.com/oauth/auth?response_type=code&scope=signature&client_id="
        . config('docusign.client_id') . "&redirect_uri=" . config('docusign.redirect_uri');
    }
}
