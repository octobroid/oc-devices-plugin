<?php namespace Octobro\Devices;

use Backend;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

/**
 * Devices Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Octobro.API'];

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

    /**
     * Registers any backend permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'octobro.devices.manage_device' => [
                'tab'   => 'device',
                'label' => 'Manage device contents from users'
            ]
        ];
    }

    /**
     * Registers backend navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'device' => [
                'label'       => 'Devices',
                'url'         => Backend::url('octobro/devices/devices'),
                'icon'        => 'icon-mobile',
                'permissions' => ['octobro.devices.manage_device'],
                'order'       => 440,
            ],
        ];
    }
}
