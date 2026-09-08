<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('subtitle')->nullable()->after('slug');
            $table->string('tag')->nullable()->after('subtitle');
            $table->string('floor')->nullable()->after('tag');
            $table->string('bed_type')->nullable()->after('floor');
            $table->string('room_size')->nullable()->after('bed_type');
            $table->integer('stars')->default(4)->after('room_size');
            $table->json('highlights')->nullable()->after('stars');
        });
    }

    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'subtitle',
                'tag',
                'floor',
                'bed_type',
                'room_size',
                'stars',
                'highlights',
            ]);
        });
    }
};
