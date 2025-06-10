<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('model');
            $table->string('type');
            $table->timestamp('installation_date')->nullable();
            $table->timestamp('last_service_date')->nullable();
            $table->timestamp('next_service_date')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_address')->nullable();
            $table->boolean('has_rtc_error')->default(false);
            $table->timestamp('rtc_error_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purifiers');
    }
}; 