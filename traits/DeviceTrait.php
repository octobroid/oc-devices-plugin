<?php namespace Octobro\Devices\Traits;

use Cache;
use RainLab\User\Models\User;
trait DeviceTrait
{
    public function getDeviceTokensAttribute() : array
    {
        // First, define property $model in construct first if it's traits Behavior Class
        if($this instanceof User){
            $model = $this;
        }else{
            $model = $this->model;
        }

        // By Caching first
        $cacheName = sprintf('%s_token_devices', $model->id);
        if(Cache::has($cacheName)){
            return (array) Cache::get($cacheName);
        }

        // Make Sure the Relation name is "devices"
        return (array) $model->devices()
        ->select('push_token')
        ->remember(5)
        ->pluck('push_token')
        ->toArray();
    }
}