<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\Messaging\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendRentalReminderSms extends Command
{
    protected $signature = 'sms:send-rental-reminders';

    protected $description = 'Send rental renewal reminder SMS for active subscriptions nearing their end date';

    public function handle(SmsService $smsService): int
    {
        $templateId = config('services.msg91.reminder_template_id');
        $lookbackDays = (int) config('services.msg91.reminder_lookback_days', 7);

        Log::info('Rental reminder SMS dispatch started.', [
            'window_start' => now()->subDays($lookbackDays)->toDateString(),
            'window_end' => now()->addDays(3)->toDateString(),
            'template_configured' => filled($templateId),
        ]);

        if (!$templateId) {
            $this->error('MSG91 reminder template ID is not configured.');
            Log::error('Rental reminder SMS dispatch stopped: MSG91 reminder template ID is not configured.');
            return self::FAILURE;
        }

        $subscriptions = Subscription::with(['customer', 'plan'])
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '>=', now()->subDays($lookbackDays))
            ->whereDate('end_date', '<=', now()->addDays(3))
            // A newer active subscription means this one has been renewed/replaced.
            // Do not remind customers about a superseded subscription still marked active.
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('subscriptions as newer_subscriptions')
                    ->whereColumn('newer_subscriptions.customer_id', 'subscriptions.customer_id')
                    ->where('newer_subscriptions.status', 'active')
                    ->where(function ($query) {
                        $query->whereColumn('newer_subscriptions.end_date', '>', 'subscriptions.end_date')
                            ->orWhere(function ($query) {
                                $query->whereColumn('newer_subscriptions.end_date', 'subscriptions.end_date')
                                    ->whereColumn('newer_subscriptions.id', '>', 'subscriptions.id');
                            });
                    });
            })
            ->get();

        Log::info('Rental reminder SMS eligible subscriptions found.', [
            'subscription_count' => $subscriptions->count(),
        ]);

        foreach ($subscriptions as $subscription) {
            $customer = $subscription->customer;

            if (!$customer || !$customer->phone) {
                Log::warning('Rental reminder SMS skipped: customer or phone is missing.', [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $subscription->customer_id,
                ]);
                continue;
            }

            $reminderDate = optional($subscription->end_date)->format('Y-m-d');
            $cacheKey = 'rental_reminder_sent_' . $subscription->id . '_' . $reminderDate;

            if (!$reminderDate || Cache::has($cacheKey)) {
                Log::info('Rental reminder SMS skipped: already sent today or end date is unavailable.', [
                    'subscription_id' => $subscription->id,
                    'end_date' => $reminderDate,
                    'phone_last_four' => substr($customer->phone, -4),
                    'already_sent' => Cache::has($cacheKey),
                ]);
                continue;
            }

            $result = $smsService->sendFlowSms($customer->phone, $templateId, [
                'name' => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: $customer->phone,
                'number' => number_format((float) optional($subscription->plan)->price, 2, '.', ''),
                'date' => optional($subscription->end_date)->format('d-m-Y'),
            ]);

            if ($result['ok']) {
                Cache::put($cacheKey, true, now()->endOfDay());
                $this->info('Reminder sent for subscription #' . $subscription->id);
                Log::info('Rental reminder SMS sent.', [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $customer->id,
                    'phone_last_four' => substr($customer->phone, -4),
                    'end_date' => $reminderDate,
                ]);
                continue;
            }

            $this->warn('Failed to send reminder for subscription #' . $subscription->id);
            Log::error('Rental reminder SMS failed.', [
                'subscription_id' => $subscription->id,
                'customer_id' => $customer->id,
                'phone_last_four' => substr($customer->phone, -4),
                'end_date' => $reminderDate,
                'message' => $result['message'],
            ]);
        }

        $this->info('Rental reminder SMS dispatch completed.');
        Log::info('Rental reminder SMS dispatch completed.');

        return self::SUCCESS;
    }
}
