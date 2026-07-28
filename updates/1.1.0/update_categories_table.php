<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;
use Winter\Storm\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('aic_faq_categories', 'slug')) {
            Schema::table('aic_faq_categories', function ($table) {
                $table->string('slug')->after('name')->nullable()->index();
            });
        }

        if (!Schema::hasColumn('aic_faq_categories', 'sort_order')) {
            Schema::table('aic_faq_categories', function ($table) {
                $table->integer('sort_order')->after('slug')->default(1)->index();
            });
        }

        if (!Schema::hasColumn('aic_faq_categories', 'is_published')) {
            Schema::table('aic_faq_categories', function ($table) {
                $table->integer('is_published')->after('sort_order')->default(1);
            });
        }

        // generate slugs and sort order for existing categories
        $categories = \DB::table('aic_faq_categories')->whereNull('slug')->get();
        foreach ($categories as $category) {
            \DB::table('aic_faq_categories')->where('id', $category->id)->update([
                'slug' => Str::slug($category->name),
                'sort_order' => $category->id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['sort_order', 'is_published', 'slug'] as $column) {
            if (Schema::hasColumn('aic_faq_categories', $column)) {
                Schema::table('aic_faq_categories', function ($table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
