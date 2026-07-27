<?php namespace Aic\Faq\Controllers;
use Backend\Classes\Controller;

use BackendMenu;

class Faqs extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    /**
     * @var string Body class property used for customising the layout
     */
    public $bodyClass = 'compact-container';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aic.Faq', 'faq', 'faqs');
    }
}
