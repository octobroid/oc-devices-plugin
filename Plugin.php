<?php namespace Octobro\Devices;

use Backend;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

/**
 * Devices Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Octobro.API', 'Octobro.OAuth2'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Devices',
            'description' => 'No description provided yet...',
            'author'      => 'Octobro',
            'icon'        => 'icon-mobile'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        User::extend(function ($model) {
            $model->hasMany['devices'] = 'Octobro\Devices\Models\Device';
        });
    }
}
