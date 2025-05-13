<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            // Add nullable for existing photos, or decide on a default album
            // onDelete('cascade') means if an album is deleted, all its photos are also deleted.
            // Use onDelete('set null') if you want photos to become "orphaned" instead.
            $table->foreignId('album_id')
                  ->nullable() // Make nullable initially or handle existing photos
                  ->after('id') // Position the column
                  ->constrained('albums') // Links to id column on albums table
                  ->onDelete('cascade'); // Or 'set null'
        });
    }

    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            // Drop foreign key first (important!) - name follows convention: table_column_foreign
            $table->dropForeign(['album_id']);
            $table->dropColumn('album_id');
        });
    }
};