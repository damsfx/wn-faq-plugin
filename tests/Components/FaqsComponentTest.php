<?php

namespace Aic\Faq\Tests\Components;

use Aic\Faq\Components\Faqs;
use Aic\Faq\Tests\FaqPluginTestCase;

class FaqsComponentTest extends FaqPluginTestCase
{
    public function testInvalidCategoryIdReturnsNoFaqData(): void
    {
        $component = new Faqs(null, [
            'sort' => 'category_id asc',
            'categoryId' => 999999,
            'isFeatured' => null,
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
        $cat1 = $this->createCategory('Cat Alpha');
        $cat2 = $this->createCategory('Cat Beta');

        $this->createFaq($cat1->id, 1, 0, 'Q1', 'A1');
        $this->createFaq($cat2->id, 1, 0, 'Q2', 'A2');

        $componentDesc = new Faqs(null, [
            'sort' => 'category_id desc',
            'isFeatured' => null,
            'isTranslated' => false,
        ]);
        $componentDesc->onRun();

        $categoriesDescNames = array_map(fn ($g) => $groupName = $g['name'], array_values($componentDesc->faqsPerCategory));
        $this->assertSame(['Cat Beta', 'Cat Alpha'], $categoriesDescNames);

        $componentAsc = new Faqs(null, [
            'sort' => 'category_id asc',
            'isFeatured' => null,
            'isTranslated' => false,
        ]);
        $componentAsc->onRun();

        $categoriesAscNames = array_map(fn ($g) => $g['name'], array_values($componentAsc->faqsPerCategory));
        $this->assertSame(['Cat Alpha', 'Cat Beta'], $categoriesAscNames);
    }

    public function testFaqsPerCategoryGroupsByCategoryIdNotName(): void
    {
        $cat1 = $this->createCategory('General');
        $cat2 = $this->createCategory('General');

        $this->createFaq($cat1->id, 1, 0, 'Q1', 'A1');
        $this->createFaq($cat2->id, 1, 0, 'Q2', 'A2');

        $component = new Faqs(null, [
            'sort' => 'category_id asc',
            'isFeatured' => null,
            'isTranslated' => false,
        ]);
        $component->onRun();

        $this->assertCount(2, $component->faqsPerCategory);
        $this->assertArrayHasKey($cat1->id, $component->faqsPerCategory);
        $this->assertArrayHasKey($cat2->id, $component->faqsPerCategory);
    }
}
