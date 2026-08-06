<?php

namespace Aic\Faq\Tests;

use Aic\Faq\Components\FaqsBySlug;
use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs;
use PluginTestCase;

class FaqsBySlugComponentTest extends PluginTestCase
{
    public function testLoadsFaqsForMatchingCategorySlug(): void
    {
        $matchedCategory = $this->createCategory('Getting started');
        $otherCategory = $this->createCategory('Advanced');

        $expectedFaq = $this->createFaq($matchedCategory->id, 1, 1, 'What is this plugin?', 'A FAQ plugin.');
        $this->createFaq($otherCategory->id, 1, 1, 'How to configure?', 'Read docs.');

        $component = new FaqsBySlug(null, [
            'categoryFilter' => $matchedCategory->slug,
            'sort' => 'question asc',
            'isFeatured' => 2,
            'isTranslated' => true,
        ]);

        $component->onRun();

        $this->assertNotNull($component->faqs);
        $this->assertCount(1, $component->faqs);
        $this->assertSame($expectedFaq->id, $component->faqs->first()->id);
        $this->assertNotEmpty($component->faqsPerCategory);
        $this->assertNotSame('', $component->jsonLd);
    }

    public function testInvalidCategorySlugReturnsNoFaqs(): void
    {
        $category = $this->createCategory('General');
        $this->createFaq($category->id, 1, 0, 'Visible FAQ', 'Visible answer');

        $component = new FaqsBySlug(null, [
            'categoryFilter' => 'missing-slug',
            'sort' => 'question asc',
            'isFeatured' => 2,
            'isTranslated' => true,
        ]);

        $component->onRun();

        $this->assertNotNull($component->faqs);
        $this->assertTrue($component->faqs->isEmpty());
        $this->assertSame([], $component->faqsPerCategory);
        $this->assertSame('', $component->jsonLd);
    }

    public function testEmptyCategoryFilterLoadsAllPublishedFaqs(): void
    {
        $categoryA = $this->createCategory('Category A');
        $categoryB = $this->createCategory('Category B');

        $faqA = $this->createFaq($categoryA->id, 1, 0, 'Question A', 'Answer A');
        $faqB = $this->createFaq($categoryB->id, 1, 0, 'Question B', 'Answer B');
        $this->createFaq($categoryB->id, 0, 0, 'Hidden', 'Hidden');

        $component = new FaqsBySlug(null, [
            'categoryFilter' => '',
            'sort' => 'question asc',
            'isFeatured' => 2,
            'isTranslated' => true,
        ]);

        $component->onRun();

        $this->assertNotNull($component->faqs);
        $ids = $component->faqs->pluck('id')->all();
        sort($ids);

        $expectedIds = [$faqA->id, $faqB->id];
        sort($expectedIds);

        $this->assertSame($expectedIds, $ids);
    }

    public function testDefinePropertiesExposesCategoryFilterWithoutSearchProperties(): void
    {
        $component = new FaqsBySlug(null, []);
        $properties = $component->defineProperties();

        $this->assertArrayHasKey('categoryFilter', $properties);
        $this->assertArrayNotHasKey('isSearch', $properties);
        $this->assertArrayNotHasKey('minSearchResults', $properties);
    }

    private function createCategory(string $name): Categories
    {
        $category = new Categories();
        $category->name = $name;
        $category->save();

        return $category;
    }

    private function createFaq(int $categoryId, int $isPublished, int $isFeatured, string $question, string $answer): Faqs
    {
        $faq = new Faqs();
        $faq->category_id = $categoryId;
        $faq->is_published = $isPublished;
        $faq->is_featured = $isFeatured;
        $faq->question = $question;
        $faq->answer = $answer;
        $faq->save();

        return $faq;
    }
}
