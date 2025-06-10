<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purifiers', function (Blueprint $table) {
            $table->boolean('has_rtc_error')->default(false)->after('location_address');
            $table->timestamp('rtc_error_updated_at')->nullable()->after('has_rtc_error');
        });
    }

    public function down()
    {
        Schema::table('purifiers', function (Blueprint $table) {
            $table->dropColumn(['has_rtc_error', 'rtc_error_updated_at']);
        });
    }
}; 