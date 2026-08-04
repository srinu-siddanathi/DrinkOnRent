<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\Messaging\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SendServiceReminderSms extends Command
{
    protected $signature = 'sms:send-service-reminders';

    protected $description = 'Send service/rental reminder SMS for customers with upcoming reminder dates';

    public function handle(SmsService $smsService): int
    {
        $templateId = config('services.msg91.reminder_template_id');

        if (!$templateId) {
            $this->error('MSG91 reminder template ID is not configured.');
            return self::FAILURE;
        }

        $services = Service::with('customer')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(3))
            ->get();

        foreach ($services as $service) {
            $customer = $service->customer;

            if (!$customer || !$customer->phone) {
                continue;
            }

            $reminderDate = optional($service->expiry_date)->format('Y-m-d');
            $cacheKey = 'service_reminder_sent_' . $service->id . '_' . $reminderDate;

            if (!$reminderDate || Cache::has($cacheKey)) {
                continue;
            }

            $result = $smsService->sendFlowSms($customer->phone, $templateId, [
                'customer_name' => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: $customer->phone,
                'expiry_date' => optional($service->expiry_date)->format('d-m-Y'),
                'service_date' => optional($service->service_date)->format('d-m-Y'),
                'next_service_reminder' => $service->next_service_reminder,
            ]);

            if ($result['ok']) {
                Cache::put($cacheKey, true, now()->endOfDay());
                $this->info('Reminder sent for service #' . $service->id);
                continue;
            }

            $this->warn('Failed to send reminder for service #' . $service->id);
        }

        $this->info('Service reminder SMS dispatch completed.');

        return self::SUCCESS;
    }
}