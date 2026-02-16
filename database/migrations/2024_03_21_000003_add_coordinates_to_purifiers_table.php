<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purifiers', function (Blueprint $table) {
            if (!Schema::hasColumn('purifiers', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('purifiers', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('purifiers', 'location_address')) {
                $table->string('location_address')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('purifiers', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_address']);
        });
    }
}; 