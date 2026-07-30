<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;
use Winter\Storm\Support\Str;

return new class extends Migration
{
    protected $migrationTable = 'aic_faq_categories';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn($this->migrationTable, 'slug')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->string('slug')->after('name')->nullable()->index();
            });
        }

        if (!Schema::hasColumn($this->migrationTable, 'sort_order')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->integer('sort_order')->after('slug')->default(1)->index();
            });
        }

        if (!Schema::hasColumn($this->migrationTable, 'is_published')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->integer('is_published')->after('sort_order')->default(1);
            });
        }

        // generate slugs and sort order for existing categories
        $categories = \DB::table($this->migrationTable)->whereNull('slug')->get();
        $sortOrder = 1;
        foreach ($categories as $category) {
            \DB::table($this->migrationTable)->where('id', $category->id)->update([
                'slug' => Str::slug($category->name),
                'sort_order' => $sortOrder++,
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
            if (Schema::hasColumn($this->migrationTable, $column)) {
                Schema::table($this->migrationTable, function ($table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
