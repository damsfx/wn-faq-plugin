# FAQ plugin

Create and manage Frequently Asked Questions with ease! Full support for translation via [Winter.Translate](https://github.com/wintercms/wn-translate-plugin) if needed.

![FAQ list](https://github.com/AIC-BV/wn-faq-plugin/blob/main/.github/assets/faq_list.jpg?raw=true)

![FAQ editing](https://github.com/AIC-BV/wn-faq-plugin/blob/main/.github/assets/faq_edit.jpg?raw=true)

## Features

With the FAQ plugin, you can:

- Full translation support if you are using Winter.Translate (optional)
- Manage your FAQ, questions and answers
- Manage FAQ's categories, can be used to group FAQ's together
- Control published FAQ's status
    - Published
    - In progress (allows logged in backend users to see them on the frontend)
    - Hidden
- Control featured FAQ's status
    - Featured
    - Not featured
- Choose the sorting method between predefined choices
- First class components to display your FAQ's on the frontend
    - Choose which categories you wish to display
    - Choose which FAQs you wish to display according to their status
- Enable a search field, allowing users to quickly find FAQs
  - You can choose the minimum amount of results that are required for displaying the search field
- FAQs are filtered by locale: only entries translated into the user's current language are displayed.  
An FAQ without a Spanish translation stays hidden for Spanish users, even if it exists in English or French.
- The default markup uses [HTML5 `<details>` and `<summary>`](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/details) elements, giving each FAQ native open/close behavior with no JavaScript required.  
Since it's just plain, semantic HTML, the markup is easy to override or restyle to match your theme.

> Notes:  
> If you are NOT using Winter.Translate, everything will work exactly the same but the 'Translated FAQs only' component property will be useless and there will be no translation support.  
> 
> The FAQ plugin comes without JavaScript and without CSS. You must style this yourself, so that it will fit your website brand.
>
> You can override : 
> - [Translations](https://wintercms.com/docs/plugin/localization#overriding)
> - [Default markup](https://wintercms.com/docs/cms/components#overriding-partials)
> - [The plugin himself](https://wintercms.com/docs/plugin/extending)

## Default behaviour:

- Automatically displays all FAQ catagories
- Automatically displays all FAQs regardless of their featured status
- Automatically adds a search box if there are more than 10 FAQs in total
    - You can change this on the component itself by changing 'Search minimum results'.
    - Not shown if 'Search enabled' is unchecked
- Automatically hides FAQs that are not translated in the current website langauge
    - You can change this on the component itself by unchecking 'Translated FAQs only'.

## Installation

The plugin will be available on the WinterCMS Marketplace as soon as the WinterCMS team release it.

### Using composer
Just run this command
```bash
composer require aic/wn-faq-plugin
```

### Clone
Clone this repo into your winter plugins folder.

```bash
cd plugins
mkdir aic && cd aic
git clone https://github.com/AIC-BV/wn-faq-plugin faq
```
**In both cases, you will need to run `php artisan winter:up` to create plugin's database tables.**
You can also log off and log back in to the backend to make sure the plugin is fully installed.

## FAQ variables

In the component itself you can use the following variables (note that you should prepend them with [{{ `__SELF__` }}](https://wintercms.com/docs/plugin/components#referencing-self) if you have multiple FAQ components on one page):  

- items (array of items)
    - name (= category name)
    - faqs (array of faqs)
        - id
        - category_id
        - is_published
        - is_featured
        - question
        - answer
        - created_at
        - updated_at
- isSearch (if true, searchbox is enabled)
- searchLabel (label for the search field)
- searchPlaceholder (placeholder for the search field)
- minSearchResults (the minimum amount of results required for displaying the searchbox)
- searchQuery (the querystring user used in the search box)

## Let me know what you think

I spent a lot of time making this plugin public for the community. All I ask in return is that you [let me know](https://github.com/AIC-BV) that you are using my plugin.
I'm sure you all understand that it is very nice for me to know if my plugin is being used or not (might make more in the future if people actually use my plugins).

You can do so by sending me a simple message on Discord (Makalele#4465) or an e-mail to __info@aic-bv.be__. It doesn't have to be much, a thank you is all I ask for :)

## Special thanks

Special thanks to the WinterCMS maintainer team for making this possible:

- [Ben Thomson](https://github.com/bennothommo)
- [Jack Wilkinson](https://github.com/jaxwilko)
- [Luke Towers](https://github.com/LukeTowers)
- [Marc Jauvin](https://github.com/mjauvin)
- [Damien Mathieu](https://github.com/damsfx)

***
Make awesome sites with ❄ [WinterCMS](https://wintercms.com) !
