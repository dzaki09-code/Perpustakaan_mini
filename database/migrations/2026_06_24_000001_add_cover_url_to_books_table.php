<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('books', 'cover_url')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('cover_url', 2048)->nullable()->after('description');
            });
        }

        if (Schema::hasColumn('books', 'read_url')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('read_url');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('books', 'read_url')) {
            Schema::table('books', function (Blueprint $table) {
                $table->string('read_url')->nullable()->after('description');
            });
        }

        if (Schema::hasColumn('books', 'cover_url')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('cover_url');
            });
        }
    }
};
