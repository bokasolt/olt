<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\AhrefsSync;
use App\Models\Domain;

/**
 * Class DashboardController.
 */
class AhrefsJobController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.dashboard-failed');
    }

    public function createJob()
    {
        Domain::query()
            ->where(function ($q) {
                $q->whereNull('ahrefs_sync_at')
                    ->orWhere('ahrefs_sync_at', '<', now()->subMinutes(config('services.ahrefs.sync_timeout'))->toDateTimeString());
            })
            ->excludeTest()
            ->update(['ahrefs_sync_queue' => null, 'ahrefs_sync_queue_at' => null]);

        dispatch(new AhrefsSync());

        for ($i = 0; $i < config('services.ahrefs.jobs_count'); $i++) {
            dispatch(new AhrefsSync())->onQueue('ahrefs' . $i);
        }
        return redirect()->route('admin.dashboard')
            ->withJobProgress(__('Ahrefs sync job started'));
    }
}
