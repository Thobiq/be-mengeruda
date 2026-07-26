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
        if (Schema::hasTable('tourism_events') && !Schema::hasColumn('tourism_events', 'description')) {
            Schema::table('tourism_events', function (Blueprint $table) {
                $table->longText('description')->nullable()->after('location');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tourism_events') && Schema::hasColumn('tourism_events', 'description')) {
            Schema::table('tourism_events', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
