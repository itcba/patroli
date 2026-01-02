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
        Schema::table('patrols', function (Blueprint $table) {
            $table->string('patrol_image')->nullable()->after('patrol_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patrols', function (Blueprint $table) {
            $table->dropColumn('patrol_image');
        });
    }
};
