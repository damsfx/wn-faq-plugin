<?php

namespace Aic\Faq\Components;

use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as Faq;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Lang;
use Winter\Storm\Database\Collection;

class Faqs extends ComponentBase
{
    /**
     * A collection of faqs to display
     */
    private Collection|null $faqs;

    /**
     * Array of faq grouped by category
     */
    public array $faqsPerCategory;

    /**
     * JSON-LD structured data for the FAQs
     */
    public string $jsonLd = '';

    /**
     * Whether to show the search form
     */
    public bool $isSearch;

    /**
     * Whether the search form should be rendered
     */
    public bool $canShowSearch;

    /**
     * The search query
     */
    public string $searchQuery;

    /**
     * The message to show when no FAQs are found
     */
    public string $noFaqsMessage;


    /**
     * Gets the details for the component
     *
     * @return array<string, string>
     */
    public function componentDetails(): array
    {
        return [
            'name'        => 'aic.faq::lang.components.faqs.title',
            'description' => 'aic.faq::lang.components.faqs.description',
        ];
    }

    /**
     * Returns the properties provided by the component
     *
     * @return array<string, mixed>
     */
    public function defineProperties(): array
    {
        return [
            'sort' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.sort.title',
                'description' => 'aic.faq::lang.components.faqs.properties.sort.description',
                'type'        => 'dropdown',
                'default'     => 'question asc',
            ],
            'categoryId' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.category.title',
                'description' => 'aic.faq::lang.components.faqs.properties.category.description',
                'type'        => 'dropdown',
                'default'     => 0
            ],
            'isFeatured' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.featured.title',
                'description' => 'aic.faq::lang.components.faqs.properties.featured.description',
                'type'        => 'dropdown',
                'default'     => 2,
                'options'     => 'aic.faq::lang.components.faqs.properties.featured.options',
            ],
            'isTranslated' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.translated.title',
                'description' => 'aic.faq::lang.components.faqs.properties.translated.description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
            'isSearch' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.search.title',
                'description' => 'aic.faq::lang.components.faqs.properties.search.description',
                'default'     => true,
                'type'        => 'checkbox',
                'group'       => 'aic.faq::lang.components.faqs.properties.search_group',
            ],
            'minSearchResults' => [
                'title'             => 'aic.faq::lang.components.faqs.properties.minSearchResults.title',
                'description'       => 'aic.faq::lang.components.faqs.properties.minSearchResults.description',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'aic.faq::lang.components.faqs.properties.minSearchResults.validationMessage',
                'group'             => 'aic.faq::lang.components.faqs.properties.search_group',
            ],
            'noFaqsMessage' => [
                'title'             => 'aic.faq::lang.components.faqs.properties.no_faqs.title',
                'description'       => 'aic.faq::lang.components.faqs.properties.no_faqs.description',
                'type'              => 'string',
                'default'           => Lang::get('aic.faq::lang.components.faqs.no_results'),
                'showExternalParam' => false
            ],
        ];
    }

    /**
     * Sort options getter
     */
    public function getSortOptions(): array
    {
        // Get Faqs::$allowedSorting to filter the options
        // Replace _asc and _desc with asc and desc in the keys of the options array
        $allowedSorting = Faq::$allowedSorting;

        return collect(Lang::get('aic.faq::lang.sorting'))
            ->filter(fn ($label, $key) => in_array(preg_replace('/_(asc|desc)$/', ' $1', $key), $allowedSorting))
            ->mapWithKeys(fn ($label, $key) => [preg_replace('/_(asc|desc)$/', ' $1', $key) => $label])
            ->all();
    }

    /**
     * Returns all categories as dropdown options.
     *
     * @return array<int, string>
     */
    public function getCategoryIdOptions(): array
    {
        // return all categories for the dropdown
        $categories = Categories::lists('name', 'id');
        $categories[0] = 'aic.faq::lang.components.faqs.properties.category.all';
        ksort($categories);

        return $categories;
    }

    /**
     * {@inheritDoc}
     */
    public function onRun(): void
    {
        $this->prepareVars();
        $categoryId = (int) $this->property('categoryId');

        if ($categoryId !== 0 && !Categories::whereKey($categoryId)->exists()) {
            $this->canShowSearch = false;
            $this->faqs = new Collection();
            $this->faqsPerCategory = [];

            return;
        }

        $this->faqs = $this->getFAQs();
        $this->faqsPerCategory = $this->faqsPerCategory();
        $this->jsonLd = $this->getJsonLd();
        $this->canShowSearch = $this->showSearch();
    }

    /**
     * Prepare variables for the page and the component
     */
    protected function prepareVars()
    {
        $this->noFaqsMessage = $this->page['noFaqsMessage'] = (string) $this->property('noFaqsMessage');
        $this->isSearch      = $this->page['isSearch']      = (bool) $this->property('isSearch');
        $this->searchQuery   = $this->page['searchQuery']   = (string) trim(input('q'));
    }

    /**
     * Get the FAQs based on the component properties
     */
    protected function getFAQs(): Collection
    {
        $faqs = Faq::with('category')->listFrontEnd([
            'sort'         => $this->property('sort'),
            'categoryId'   => (int) $this->property('categoryId'),
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => (bool) $this->property('isSearch'),
            'isTranslated' => (int) $this->property('isTranslated'),
            'searchQuery'  => $this->searchQuery
        ]);

        return $faqs;
    }

    /**
     * Group FAQs by category for frontend rendering.
     *
     * @return array<int|string, array<string, mixed>>
     */
    protected function faqsPerCategory(): array
    {
        // Group the FAQs from $this->faqs by category
        // and sort them according to the specified sort order
        $groupedFaqs = $this->faqs
            // ->groupBy('category_id')
            ->groupBy(fn ($faq) => $faq->category->name)
            ->sortBy(fn ($faqsInCategory) => $faqsInCategory->first()->category->sort_order);

        // return the new array
        return $groupedFaqs->toArray();
    }

    /**
     * Determine whether the search form should be shown.
     */
    protected function showSearch(): bool
    {
        if (!$this->isSearch) {
            return false;
        }

        $minSearchResults = (int) $this->property('minSearchResults');

        // Reuse the existing FAQs collection if no search query is provided,
        // otherwise fetch the count of FAQs without search applied.
        $faqsWithoutSearch = $this->faqs->count();

        if ($this->searchQuery !== '') {
            $faqsWithoutSearch = Faq::listFrontEnd([
                'categoryId'   => (int) $this->property('categoryId'),
                'isFeatured'   => (int) $this->property('isFeatured'),
                'isSearch'     => false,
                'isTranslated' => (int) $this->property('isTranslated')
            ])->count();
        }

        return $faqsWithoutSearch >= $minSearchResults;
    }

    /**
     * Generate JSON-LD structured data for the FAQs.
     *
     * @return string
     */
    public function getJsonLd(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $this->faqs->flatten(1)->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $this->cleanHtmlWhitespace($faq->answer),
                    ],
                ];
            })->values()->toArray(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Collapse line breaks and repeated whitespace coming from the stored
     * HTML's own indentation/line wrapping into single spaces, so the
     * generated JSON-LD doesn't carry cosmetic \n noise from the source markup.
     *
     * @param string $html
     * @return string
     */
    protected function cleanHtmlWhitespace(string $html): string
    {
        return trim(preg_replace('/\s+/', ' ', $html));
    }
}
