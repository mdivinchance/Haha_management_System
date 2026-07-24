<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
        });

        $defaultManager = DB::table('users')->where('role', 'manager')->first();
        if ($defaultManager) {
            DB::table('categories')->whereNull('user_id')->update(['user_id' => $defaultManager->id]);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
