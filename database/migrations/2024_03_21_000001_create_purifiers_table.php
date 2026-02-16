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
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('model');
            $table->string('type');
            $table->string('status')->default('available');
            $table->timestamp('installation_date')->nullable();
            $table->timestamp('last_service_date')->nullable();
            $table->timestamp('next_service_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purifiers');
    }
}; 