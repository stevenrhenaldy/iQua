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
        Schema::create('applet_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("applet_id")->constrained("applets");
            $table->foreignId("group_id")->constrained("groups");
            $table->foreignId("device_id")->constrained("devices");
            $table->enum("type", ["trigger", "action"]);
            $table->foreignId("entity_id")->constrained("entities");
            $table->string("value");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applet_nodes');
    }
};
