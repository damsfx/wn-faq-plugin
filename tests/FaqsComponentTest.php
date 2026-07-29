<?php

namespace Aic\Faq\Tests;

use Aic\Faq\Components\Faqs;
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

    public function testFaqPartialContainsNoResultsMessageKey(): void
    {
        $partialPath = plugins_path('aic/faq/components/faqs/default.htm');
        $partialContent = file_get_contents($partialPath);

        $this->assertNotFalse($partialContent);
        $this->assertStringContainsString('aic.faq::lang.components.faqs.no_results', $partialContent);
    }
}
