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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string("serial_number")->nullable();
            $table->foreignId("group_id")->nullable();
            $table->foreignId("device_type_id")->nullable();
            $table->string("name")->nullable();
            $table->string("status")->default("offline");
            $table->timestamp("assigned_at")->nullable();
            $table->foreignId("assigned_by_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
