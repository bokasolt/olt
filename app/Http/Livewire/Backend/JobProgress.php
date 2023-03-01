<?php

namespace App\Http\Livewire\Backend;

use App\Models\Domain;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class JobProgress extends Component
{
    public function isJobProgress(): int
    {
        return DB::table('jobs')->count();
    }

    public function getFailed(): int
    {
        return Domain::whereNotNull('ahrefs_error_message')->count();
    }

    public function render()
    {
        $data = [];
        $data['poll'] = false;
        $data['left'] = Domain::query()
            ->where(function ($q) {
                $q->whereNull('ahrefs_sync_queue')
                    ->orWhereNotNull('ahrefs_sync_queue_at');
            })
            ->excludeTest()
            ->count();

        if ($data['left']) {
            $data['poll'] = $this->isJobProgress();
        }
        if (!$data['poll']) {
            $data['failed_count'] = $this->getFailed();
        }

        return view(
            'livewire.job-progress',
            $data
        );
    }
}
