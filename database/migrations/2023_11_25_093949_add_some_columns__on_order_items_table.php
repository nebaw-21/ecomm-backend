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
        Schema::table('orderItems', function (Blueprint $table) {
            $table->string('name');
            $table->string('color')->nullable();
            $table->string('size');
            $table->string('image');
            $table->string('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orderItems', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('color');
            $table->dropColumn('size');
            $table->dropColumn('image');
            $table->dropColumn('price');
        });
    }
};
