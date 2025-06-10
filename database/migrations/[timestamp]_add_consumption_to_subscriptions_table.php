<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'litres_consumed')) {
                $table->integer('litres_consumed')->default(0);
            }
            if (!Schema::hasColumn('subscriptions', 'litres_remaining')) {
                $table->integer('litres_remaining')->default(100);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['litres_consumed', 'litres_remaining']);
        });
    }
}; 