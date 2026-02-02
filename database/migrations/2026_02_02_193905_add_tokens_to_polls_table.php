<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->string('participant_token', 12)->nullable()->after('user_id');
            $table->string('admin_token', 24)->nullable()->after('participant_token');
        });

        // Generate tokens for existing polls
        DB::table('polls')->whereNull('participant_token')->orderBy('id')->each(function ($poll) {
            DB::table('polls')->where('id', $poll->id)->update([
                'participant_token' => Str::random(12),
                'admin_token' => Str::random(24),
            ]);
        });

        Schema::table('polls', function (Blueprint $table) {
            $table->string('participant_token', 12)->nullable(false)->unique()->change();
            $table->string('admin_token', 24)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropColumn(['participant_token', 'admin_token']);
        });
    }
};
