<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Mark active subscriptions as expired after their end date';

    public function handle(): int
    {
        $expiredCount = Subscription::query()
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now())
            ->update(['status' => 'expired']);

        $this->info("Marked {$expiredCount} subscription(s) as expired.");
        Log::info('Expired subscriptions updated.', ['count' => $expiredCount]);

        return self::SUCCESS;
    }
}
