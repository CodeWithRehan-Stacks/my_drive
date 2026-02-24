<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('storage_used')
                  ->default(0)
                  ->after('plan');

            $table->unsignedBigInteger('storage_limit')
                  ->default(1073741824)
                  ->after('storage_used');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['storage_used', 'storage_limit']);
        });
    }
};