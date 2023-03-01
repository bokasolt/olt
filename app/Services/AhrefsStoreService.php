<?php


namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\Domain;
use Illuminate\Support\Facades\Log;

class AhrefsStoreService
{
    protected $ahrefs;

    public function __construct(AhrefsService $ahrefs)
    {
        $this->ahrefs = $ahrefs;
    }

    public function updateDomain(Domain $domain): bool
    {
        try {
            $metrics = $this->ahrefs->getAhrefsMetrics($domain->domain);
            
            if (!isset($metrics['pages_info'])) {
	            throw new ReportableException('pages_info absent');
            }
			$this->checkError('pages_info', $metrics['pages_info']);
                        
			/* For some domains ahrefs doesn't return title, we should try request www domain */
            if (empty($metrics['pages_info']->pages) || empty($metrics['pages_info']->pages[0]->title)) {
            	$metrics_www = $this->ahrefs->getAhrefsMetrics('www.' . $domain->domain);
            	if (isset($metrics_www['pages_info'])) {
	            	$metrics['pages_info'] = $metrics_www['pages_info'];
            	}
            }
            
            //Log::info($metrics);
            $dirty = false;
            foreach ($metrics as $metric => $data) {
                $this->checkError($metric, $data);

                if (! method_exists($this, 'update_' . $metric)) {
                    throw new ReportableException('Can not parse ' . $metric);
                }

                if ($this->{'update_' . $metric}($domain, $data)) {
                    $dirty = true;
                }
            }
            $domain->ahrefs_sync_at = now();
            $domain->ahrefs_error_message = null;
            $domain->save();
        } catch (ReportableException $e) {
            Log::error($e->getMessage());
            $domain->ahrefs_error_message = $e->getMessage();
            $domain->save();

            throw $e;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            $domain->ahrefs_error_message = $e->getMessage();
            $domain->save();

            throw $e;
        }

        return $dirty;
    }

    protected function update_domain_rating(Domain $domain, $data): bool
    {
        if (! isset($data->domain->domain_rating)) {
            throw new ReportableException('Unable to parse domain_rating');
        }

        if ($domain->ahrefs_dr != $data->domain->domain_rating) {
            $domain->ahrefs_dr = $data->domain->domain_rating;

            return true;
        }

        return false;
    }

    protected function update_pages_info(Domain  $domain, $data): bool
    {
        if (! isset($data->pages )) {
            throw new ReportableException('Unable to parse pages info');
        }

        if ( empty($data->pages )) {
            if ($domain->title != '') {
                $domain->title = '';
                return true;
            }
            return false;
        }

        if ($domain->title != $data->pages[0]->title) {
            $domain->title = $data->pages[0]->title;

            return true;
        }

        return false;
    }

    protected function update_positions_metrics(Domain $domain, $data): bool
    {
        if (! isset($data->metrics->traffic) ||
            ! isset($data->metrics->positions_top10)) {
            throw new ReportableException('Unable to parse domain_rating');
        }

        $dirty = false;
        if ($domain->ahrefs_traffic != $data->metrics->traffic) {
            $domain->ahrefs_traffic = $data->metrics->traffic;

            $dirty = true;
        }

        if ($domain->num_organic_keywords_top_10 != $data->metrics->positions_top10) {
            $domain->num_organic_keywords_top_10 = $data->metrics->positions_top10;

            $dirty = true;
        }

        return $dirty;
    }

    protected function update_metrics_extended(Domain $domain, $data): bool
    {
        if (! isset($data->metrics)) {
            throw new ReportableException('Unable to parse metrics');
        }

        $dirty = false;

        if ($domain->linked_domains != $data->metrics->linked_root_domains) {
            $domain->linked_domains = $data->metrics->linked_root_domains;
            $dirty = true;
        }

        if ($domain->ref_domains != $data->metrics->refdomains) {
            $domain->ref_domains = $data->metrics->refdomains;
            $dirty = true;
        }
        return $dirty;
    }

    protected function update_refdomains(Domain $domain, $data): bool
    {
        if (! isset($data->stats->refdomains)) {
            throw new ReportableException('Unable to parse refdomains');
        }

        if ($domain->ref_domains != $data->stats->refdomains) {
            $domain->ref_domains = $data->stats->refdomains;

            return true;
        }

        return false;
    }

    protected function checkError($metric, $data)
    {
        if (isset($data->error)) {
            throw new ReportableException($metric . ': ' . $data->error);
        }
    }
}
