<?php namespace Octobro\Devices;

use Backend;
use Event;
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

        // extend the user navigation
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                 'users' => [
                    'label'       => 'rainlab.user::lang.users.menu_label',
                    'icon'        => 'icon-user',
                    'code'        => 'users',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('rainlab/user/users'),
                ],
             ]);
         });

        User::extend(function ($model) {
            $model->hasMany['devices'] = 'Octobro\Devices\Models\Device';
        });
    }
}
