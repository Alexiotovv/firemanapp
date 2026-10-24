<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('availability_categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('activo')->constrained()->cascadeOnDelete();
        });

        $assignments = DB::table('availability_category_user')
            ->orderBy('category_id')
            ->orderBy('user_id')
            ->get()
            ->groupBy('category_id');

        foreach ($assignments as $categoryId => $users) {
            $category = DB::table('availability_categories')->where('id', $categoryId)->first();
            $elements = DB::table('availability_elements')->where('category_id', $categoryId)->get();
            $firstUserId = $users->first()->user_id;

            DB::table('availability_categories')->where('id', $categoryId)->update(['user_id' => $firstUserId]);

            foreach ($users->skip(1) as $user) {
                $newCategoryId = DB::table('availability_categories')->insertGetId([
                    'nombre' => $category->nombre,
                    'icono' => $category->icono,
                    'activo' => $category->activo,
                    'user_id' => $user->user_id,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ]);

                foreach ($elements as $element) {
                    $newElementId = DB::table('availability_elements')->insertGetId([
                        'category_id' => $newCategoryId,
                        'nombre' => $element->nombre,
                        'created_at' => $element->created_at,
                        'updated_at' => $element->updated_at,
                    ]);

                    $check = DB::table('availability_checks')
                        ->where('element_id', $element->id)
                        ->where('user_id', $user->user_id)
                        ->first();

                    if ($check) {
                        DB::table('availability_checks')->insert([
                            'element_id' => $newElementId,
                            'user_id' => $check->user_id,
                            'disponible' => $check->disponible,
                            'created_at' => $check->created_at,
                            'updated_at' => $check->updated_at,
                        ]);
                    }
                }
            }

            DB::table('availability_checks')
                ->whereIn('element_id', $elements->pluck('id'))
                ->where('user_id', '!=', $firstUserId)
                ->delete();
        }

        Schema::dropIfExists('availability_category_user');
    }

    public function down(): void
    {
        Schema::create('availability_category_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('availability_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['category_id', 'user_id']);
        });

        DB::table('availability_categories')
            ->whereNotNull('user_id')
            ->get(['id', 'user_id', 'created_at', 'updated_at'])
            ->each(function ($category) {
                DB::table('availability_category_user')->insert([
                    'category_id' => $category->id,
                    'user_id' => $category->user_id,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ]);
            });

        Schema::table('availability_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
