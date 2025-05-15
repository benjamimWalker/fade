<?php

namespace App\Jobs;

use App\Models\Note;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DeleteExpiredNotes implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {

    }

    public function handle(): void
    {
        $totalDeleted = 0;

        Note::select('id')
            ->whereNotNull('expires_at')
            ->whereBeforeToday('expires_at')
            ->chunkById(1000, function (Collection $notes) use (&$totalDeleted) {
                $totalDeleted += $notes->count();
                Note::whereIn('id', $notes->pluck('id'))->delete();
            });

        Log::info("Deleted {$totalDeleted} expired notes.");
    }
}
