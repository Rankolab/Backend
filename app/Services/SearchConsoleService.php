<?php

namespace App\Services;

use Google\Client;
use Google\Service\SearchConsole;

class SearchConsoleService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-service-account.json'));
        $this->client->addScope(SearchConsole::WEBMASTERS_READONLY);

        $this->service = new SearchConsole($this->client);
    }

    public function getPerformanceReport($siteUrl, $startDate, $endDate)
    {
        $request = new SearchConsole\SearchAnalyticsQueryRequest([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dimensions' => ['query'],
            'rowLimit' => 25
        ]);

        return $this->service->searchanalytics->query($siteUrl, $request);
    }

    public function listSites()
    {
        return $this->service->sites->listSites();
    }
}
