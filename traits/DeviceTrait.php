<?php namespace Octobro\Devices\Traits;

use Cache;

trait DeviceTrait
{

    /**
     * Boot the Device trait for a model
     *
     * @return void
     */
    public static function bootDeviceTrait()
    {
        static::extend(function($model) {
            $model->bindEvent('model.beforeSave', function() use ($model) {
                $model->deviceTraitBeforeSave();
            });
        });
    }

    public function deviceTraitBeforeSave()
    {

    }

    public function getDeviceTokensAttribute() : array
    {
        // By Caching first
        $cacheName = sprintf('%s_token_devices', $this->id);
        if(Cache::has($cacheName)){
            return (array) Cache::get($cacheName);
        }

        // Make Sure the Relation name is "devices"
        return (array) $this->devices()
        ->select('push_token')
        ->remember(5)
        ->pluck('push_token')
        ->toArray();
    }
}