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
    public Collection|null $faqs;

    /**
     * Array of faq grouped by category
     */
    public array $faqsPerCategory;

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
                'default'     => 'category_id asc',
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
        ];
    }

    /**
     * Sort options getter
     */
    public function getSortOptions(): array
    {
        // Replace _asc and _desc with asc and desc in the keys of the options array
        return collect(Lang::get('aic.faq::lang.components.faqs.properties.sort.options'))
            ->mapWithKeys(fn ($label, $key) => [preg_replace('/_(asc|desc)$/', ' $1', $key) => $label])
            ->all();
    }

    /**
     * {@inheritDoc}
     */
    public function onRun(): void
    {
        $this->isSearch = (bool) $this->property('isSearch');
        $this->searchQuery = (string) trim(input('q'));

        $categoryId = (int) $this->property('categoryId');
        if ($categoryId !== 0 && !Categories::whereKey($categoryId)->exists()) {
            $this->canShowSearch = false;
            $this->faqs = new Collection();
            $this->faqsPerCategory = [];

            return;
        }

        $this->canShowSearch = $this->showSearch();

        $this->faqs = $this->getFAQs();
        $this->faqsPerCategory = $this->faqsPerCategory($this->faqs);
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

        $faqsWithoutSearch = Faq::listFrontEnd([
            'categoryId'   => (int) $this->property('categoryId'),
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => false,
            'isTranslated' => (int) $this->property('isTranslated')
        ])->count();

        return $faqsWithoutSearch >= $minSearchResults;
    }

    /**
     * Get the FAQs based on the component properties
     */
    protected function getFAQs(): Collection
    {
        $faqs = Faq::listFrontEnd([
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
     * @param Collection $faqs
     * @return array<int|string, array<string, mixed>>
     */
    protected function faqsPerCategory(Collection $faqs): array
    {
        // get properties
        $categoryId = (int) $this->property('categoryId');
        $sort = $this->property('sort');

        // get category name
        $categoryName = $this->getCategoryName($categoryId);

        if ($categoryId !== 0 && $categoryName === null) {
            return [];
        }

        // if category name is not 0 (all)
        if ($categoryName !== 0) {
            // return the FAQs with their category
            return [
                [
                    'categoryName' => $categoryName,
                    'faqs' => $faqs
                ]
            ];
        } else {
            // create new array
            $newArray = [];

            // prepare the array with the categories
            $categories = [];
            if ($sort == 'category_id asc') {
                $categories = Categories::orderBy('id', 'asc')->get();
            } elseif ($sort == 'category_id desc') {
                $categories = Categories::orderBy('id', 'desc')->get();
            } else {
                $categories = Categories::get();
            }

            foreach ($categories as $category) {
                $newArray[$category->id] = [
                    'categoryName' => $category->name,
                    'faqs' => []
                ];
            }

            // push the faq to the right category
            foreach ($faqs as $faq) {
                $categoryKey = $faq->category_id;

                if (!array_key_exists($categoryKey, $newArray)) {
                    $newArray[$categoryKey] = [
                        'categoryName' => Lang::get('aic.faq::lang.components.faqs.properties.category.no_category_label'),
                        'faqs' => []
                    ];
                }

                $newArray[$categoryKey]['faqs'][] = $faq;

                // if categoryName doesn't exist
                // set no_category_label
                if (!array_key_exists('categoryName', $newArray[$categoryKey])) {
                    $newArray[$categoryKey]['categoryName'] = Lang::get('aic.faq::lang.components.faqs.properties.category.no_category_label');
                }
            }

            // remove empty categories
            foreach ($newArray as $key => $value) {
                $amountFaqs = count($value['faqs']);
                if ($amountFaqs == 0) {
                    unset($newArray[$key]);
                };
            }

            // return the new array
            return $newArray;
        }
    }

    /**
     * Resolve the selected category name.
     *
     * @param int $categoryId
     * @return string|int|null
     */
    protected function getCategoryName(int $categoryId): string|int|null
    {
        if ($categoryId == 0) {
            return 0;
        }

        $category = Categories::find($categoryId);

        return $category?->name;
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
}
