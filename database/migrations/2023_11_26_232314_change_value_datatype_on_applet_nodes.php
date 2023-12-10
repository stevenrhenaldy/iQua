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
        // Schema::disableForeignKeyConstraints();
        Schema::table('applet_nodes', function (Blueprint $table) {
            $table->longText("value")->nullable()->change();
            $table->dropForeign('applet_nodes_device_id_foreign');
            $table->bigInteger("device_id")->index()->change();
        });
        // Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applet_nodes', function (Blueprint $table) {
            $table->string("value")->change();
            $table->foreignId("device_id")->constrained("devices")->change();
        });
    }
};
