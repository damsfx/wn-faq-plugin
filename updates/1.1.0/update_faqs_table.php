<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    protected $migrationTable = 'aic_faq_faqs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn($this->migrationTable, 'sort_order')) {
            Schema::table($this->migrationTable, function ($table) {
                $table->integer('sort_order')->after('answer')->default(1)->index();
            });
        }

        // generate sort order for existing faqs in each category
        $categories = \DB::table('aic_faq_categories')->get();
        foreach ($categories as $category) {
            $sortOrder = 1;
            $faqs = \DB::table($this->migrationTable)
                ->where('category_id', $category->id)
                ->orderBy('id')
                ->get();
            foreach ($faqs as $faq) {
                \DB::table($this->migrationTable)->where('id', $faq->id)->update([
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['sort_order'] as $column) {
            if (Schema::hasColumn($this->migrationTable, $column)) {
                Schema::table($this->migrationTable, function ($table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
