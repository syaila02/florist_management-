<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flowers', function (Blueprint $table) {
            $table->string('color', 50)->nullable()->after('flower_name');
            $table->enum('target_gender', ['Female', 'Male', 'General'])->default('General')->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('flowers', function (Blueprint $table) {
            $table->dropColumn(['color', 'target_gender']);
        });
    }
};
