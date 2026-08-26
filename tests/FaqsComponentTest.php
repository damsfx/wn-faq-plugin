<?php

namespace Aic\Faq\Tests;

use Aic\Faq\Components\Faqs;
use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as FaqModel;
use PluginTestCase;

class FaqsComponentTest extends PluginTestCase
{
    public function testInvalidCategoryIdReturnsNoFaqData(): void
    {
        $component = new Faqs(null, [
            'sort' => 'category_id asc',
            'categoryId' => 999999,
            'isFeatured' => 2,
            'isTranslated' => true,
            'isSearch' => true,
            'minSearchResults' => 10,
        ]);

        $component->onRun();

        $this->assertTrue($component->faqs->isEmpty());
        $this->assertSame([], $component->faqsPerCategory);
    }

    public function testFaqsPerCategoryPreservesCategoryIdSortOrder(): void
    {
        $cat1 = new Categories();
        $cat1->name = 'Cat Alpha';
        $cat1->is_published = 1;
        $cat1->save();

        $cat2 = new Categories();
        $cat2->name = 'Cat Beta';
        $cat2->is_published = 1;
        $cat2->save();

        $faq1 = new FaqModel();
        $faq1->category_id = $cat1->id;
        $faq1->question = 'Q1';
        $faq1->answer = 'A1';
        $faq1->is_published = 1;
        $faq1->is_featured = 0;
        $faq1->save();

        $faq2 = new FaqModel();
        $faq2->category_id = $cat2->id;
        $faq2->question = 'Q2';
        $faq2->answer = 'A2';
        $faq2->is_published = 1;
        $faq2->is_featured = 0;
        $faq2->save();

        $componentDesc = new Faqs(null, [
            'sort' => 'category_id desc',
            'isFeatured' => 2,
            'isTranslated' => false,
        ]);
        $componentDesc->onRun();

        $categoriesDescNames = array_map(fn ($g) => $groupName = $g['name'], array_values($componentDesc->faqsPerCategory));
        $this->assertSame(['Cat Beta', 'Cat Alpha'], $categoriesDescNames);

        $componentAsc = new Faqs(null, [
            'sort' => 'category_id asc',
            'isFeatured' => 2,
            'isTranslated' => false,
        ]);
        $componentAsc->onRun();

        $categoriesAscNames = array_map(fn ($g) => $g['name'], array_values($componentAsc->faqsPerCategory));
        $this->assertSame(['Cat Alpha', 'Cat Beta'], $categoriesAscNames);
    }

    public function testFaqsPerCategoryGroupsByCategoryIdNotName(): void
    {
        $cat1 = new Categories();
        $cat1->name = 'General';
        $cat1->is_published = 1;
        $cat1->save();

        $cat2 = new Categories();
        $cat2->name = 'General';
        $cat2->is_published = 1;
        $cat2->save();

        $faq1 = new FaqModel();
        $faq1->category_id = $cat1->id;
        $faq1->question = 'Q1';
        $faq1->answer = 'A1';
        $faq1->is_published = 1;
        $faq1->is_featured = 0;
        $faq1->save();

        $faq2 = new FaqModel();
        $faq2->category_id = $cat2->id;
        $faq2->question = 'Q2';
        $faq2->answer = 'A2';
        $faq2->is_published = 1;
        $faq2->is_featured = 0;
        $faq2->save();

        $component = new Faqs(null, [
            'sort' => 'category_id asc',
            'isFeatured' => 2,
            'isTranslated' => false,
        ]);
        $component->onRun();

        $this->assertCount(2, $component->faqsPerCategory);
        $this->assertArrayHasKey($cat1->id, $component->faqsPerCategory);
        $this->assertArrayHasKey($cat2->id, $component->faqsPerCategory);
    }
}
