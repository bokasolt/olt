<?php


namespace App\Services;

use ahrefs\AhrefsApiPhp\AhrefsAPI;
use App\Models\Settings;

class AhrefsService
{
    private $ahrefs = null;

    public function __construct()
    {
        $this->ahrefs = new AhrefsAPI(Settings::getOption('AHREFS_TOKEN'));
    }

    protected function prepareSubdomainMetrics(string $domain)
    {
        $this->ahrefs->set_target($domain)
            ->mode_subdomains()
            ->set_limit(0);
        $this->ahrefs->set_limit(1)->prepare_pages_info();
        $this->ahrefs->prepare_domain_rating();
        $this->ahrefs->prepare_positions_metrics();
        $this->ahrefs->prepare_metrics_extended();
        //$this->ahrefs->set_limit(0)->prepare_refdomains();
    }

    public function getAhrefsMetrics(string $domain): array
    {
        $this->prepareSubdomainMetrics($domain);
        $response = $this->ahrefs->run();
        foreach ($response as &$ref_val) {
            $ref_val = json_decode($ref_val);
        }

        return $response;
    }
}
