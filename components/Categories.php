<?php

namespace Aic\Faq\Components;

use Lang;
use Aic\Faq\Models\Categories as FaqCategories;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Collection;

class Categories extends ComponentBase
{
    /**
     * A collection of categories to display
     */
    public ?Collection $categories = null;

    /**
     * Reference to the page name for linking to categories.
     */
    public ?string $categoryPage = '';

    /**
     * Reference to the page name for linking to categories.
     */
    public ?string $faqPage = '';

    /**
     * Reference to the current category slug.
     */
    public ?string $currentCategorySlug = '';

    /**
     * Gets the details for the component
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'aic.faq::lang.components.categories.title',
            'description' => 'aic.faq::lang.components.categories.description',
        ];
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties(): array
    {
        return [
            'categorySlug' => [
                'title' => 'aic.faq::lang.components.categories.properties.slug.title',
                'description' => 'aic.faq::lang.components.categories.properties.slug.description',
                'type' => 'string',
                'default' => '{{ :slug }}',
            ],
            'faqPage' => [
                'title' => 'aic.faq::lang.components.categories.properties.faq_page.title',
                'description' => 'aic.faq::lang.components.categories.properties.faq_page.description',
                'type' => 'dropdown',
                'group' => 'aic.faq::lang.components.categories.properties.links',
            ],
            'categoryPage' => [
                'title' => 'aic.faq::lang.components.categories.properties.category_page.title',
                'description' => 'aic.faq::lang.components.categories.properties.category_page.description',
                'type' => 'dropdown',
                'group' => 'aic.faq::lang.components.categories.properties.links',
            ],
        ];
    }

    /**
     * Category page options getter
     */
    public function getCategoryPageOptions(): array
    {
        return \Aic\Faq\Classes\ComponentHelper::getPagesByComponent('faqCategories');
    }


    /**
     * Category page options getter
     */
    public function getFaqPageOptions(): array
    {
        return \Aic\Faq\Classes\ComponentHelper::getPagesByComponent('FAQ');
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->property('slug');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->faqPage = $this->page['faqPage'] = $this->property('faqPage');
        $this->categories = $this->page['categories'] = $this->loadCategories();
    }

    /**
     * Get all the published faq categories, including a special "All" category at the beginning of the collection.
     *
     * @return Collection
     */
    protected function loadCategories(): Collection
    {
        $categories = FaqCategories::withCount('faqs')
            ->where('is_published', 1)
            ->get();

        // Add a special "All" category at the beginning of the collection
        $allCategory = new FaqCategories();
        $allCategory->id = 0;
        $allCategory->name = Lang::get('aic.faq::lang.components.categories.all_label');
        $allCategory->slug = '';
        $allCategory->faqs_count = $categories->sum('faqs_count');
        $categories->prepend($allCategory);

        /*
         * Add a "url" helper attribute for linking to each category
         */
        return $this->linkCategories($categories);
    }


    /**
     * Sets the URL on each category according to the defined category page
     */
    protected function linkCategories(Collection $categories): Collection
    {
        return $categories->each(function ($category) {
            $category->setUrl($this->categoryPage, $this->controller);
        });
    }
}
