<?php

namespace App\Jobs;

use App\Exceptions\ReportableException;
use App\Models\Domain;
use App\Services\AhrefsStoreService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AhrefsSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AhrefsStoreService $ahrefs)
    {
        $processed = 0;
        do {
            Domain::query()
                ->whereNull('ahrefs_sync_queue')
                ->limit(config('services.ahrefs.job_limit_window'))
                ->excludeTest()
                ->update(['ahrefs_sync_queue' => $this->queue, 'ahrefs_sync_queue_at' => now()]);
            $domains = Domain::query()
                ->where('ahrefs_sync_queue', '=', $this->queue)
                ->whereNotNull('ahrefs_sync_queue_at')->get();

            foreach ($domains as $domain) {
                try {
                    $ahrefs->updateDomain($domain);

                } catch (ReportableException $exception) {
                    //We will report later
                } catch (\Throwable $exception) {
                    //We will report later
                }
                $domain->ahrefs_sync_queue_at = null;
                $domain->update();
                $processed++;
            }
        } while ($domains->count());
        Log::info('Processed by ' . $this->queue . ': '. $processed);

        //Collect garbage
        $isSomeJobFailed = Domain::query()
            ->whereNotNull('ahrefs_sync_queue')
            ->where('ahrefs_sync_queue_at', '<',
                now()->subMinutes(config('services.ahrefs.jobs_timeout'))->toDateTimeString())
            ->update(['ahrefs_sync_queue' => null]);
        if ($isSomeJobFailed) {
            Log::alert('Hm... SomeJobFailed');
            $this->handle($ahrefs);
        }
    }
}
