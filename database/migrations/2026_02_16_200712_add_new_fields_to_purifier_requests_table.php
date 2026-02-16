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
        Schema::table('purifier_requests', function (Blueprint $table) {
            $table->string('source_of_drinking_water')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('type_of_residence')->nullable();
            $table->string('share_with')->nullable();
            $table->string('current_profession')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purifier_requests', function (Blueprint $table) {
            $table->dropColumn([
                'source_of_drinking_water',
                'date_of_birth',
                'type_of_residence',
                'share_with',
                'current_profession',
            ]);
        });
    }
};