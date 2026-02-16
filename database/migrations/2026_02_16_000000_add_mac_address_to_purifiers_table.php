<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purifiers', function (Blueprint $table) {
            if (!Schema::hasColumn('purifiers', 'mac_address')) {
                $table->string('mac_address')->nullable()->unique()->after('serial_number');
            }
        });
    }

    public function down()
    {
        Schema::table('purifiers', function (Blueprint $table) {
            if (Schema::hasColumn('purifiers', 'mac_address')) {
                $table->dropColumn('mac_address');
            }
        });
    }
};
