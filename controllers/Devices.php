<?php namespace Octobro\Devices\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Devices Back-end Controller
 */
class Devices extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Octobro.Devices', 'devices', 'devices');
    }
}
