<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function (): void {
            $this->deduplicateCards();
            $this->deduplicateCategories();
        });

        Schema::table('cards', function (Blueprint $table) {
            $table->unique(['user_id', 'name'], 'cards_user_name_unique');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name'], 'categories_user_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropUnique('cards_user_name_unique');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_user_name_unique');
        });
    }

    private function deduplicateCards(): void
    {
        $duplicateGroups = DB::table('cards')
            ->select('user_id', 'name', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as aggregate'))
            ->groupBy('user_id', 'name')
            ->having('aggregate', '>', 1)
            ->get();

        foreach ($duplicateGroups as $group) {
            $duplicateIds = DB::table('cards')
                ->where('user_id', $group->user_id)
                ->where('name', $group->name)
                ->where('id', '<>', $group->keep_id)
                ->pluck('id');

            if ($duplicateIds->isEmpty()) {
                continue;
            }

            DB::table('transactions')
                ->whereIn('card_id', $duplicateIds)
                ->update(['card_id' => $group->keep_id]);

            DB::table('cards')
                ->whereIn('id', $duplicateIds)
                ->delete();
        }
    }

    private function deduplicateCategories(): void
    {
        $duplicateGroups = DB::table('categories')
            ->select('user_id', 'name', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as aggregate'))
            ->groupBy('user_id', 'name')
            ->having('aggregate', '>', 1)
            ->get();

        foreach ($duplicateGroups as $group) {
            $categories = DB::table('categories')
                ->where('user_id', $group->user_id)
                ->where('name', $group->name)
                ->orderBy('id')
                ->get(['id', 'type']);

            if ($categories->count() <= 1) {
                continue;
            }

            $keepId = (int) $categories->first()->id;
            $duplicateIds = $categories->skip(1)->pluck('id');
            $types = $categories->pluck('type')->unique();

            $resolvedType = $types->count() > 1
                ? 'Ambos'
                : (string) $types->first();

            DB::table('categories')
                ->where('id', $keepId)
                ->update([
                    'type' => $resolvedType,
                    'updated_at' => now(),
                ]);

            if ($duplicateIds->isEmpty()) {
                continue;
            }

            DB::table('transactions')
                ->whereIn('category_id', $duplicateIds)
                ->update(['category_id' => $keepId]);

            DB::table('categories')
                ->whereIn('id', $duplicateIds)
                ->delete();
        }
    }
};
