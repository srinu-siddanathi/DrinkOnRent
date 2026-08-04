<?php

use App\Console\Commands\SendServiceReminderSms;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::resolveCommands([
    SendServiceReminderSms::class,
]);

Schedule::command('sms:send-service-reminders')->dailyAt('09:00');
