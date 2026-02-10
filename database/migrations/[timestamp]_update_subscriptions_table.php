<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'purifier_id')) {
                $table->foreignId('purifier_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('subscriptions', 'litres_consumed')) {
                $table->integer('litres_consumed')->default(0);
            }
            if (!Schema::hasColumn('subscriptions', 'litres_remaining')) {
                $table->integer('litres_remaining')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['purifier_id']);
            $table->dropColumn(['purifier_id', 'status', 'litres_consumed', 'litres_remaining']);
        });
    }
}; 