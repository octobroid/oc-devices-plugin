<?php namespace Octobro\Devices\Classes;

use Cache;
use Octobro\Devices\Models\Device;

class DeviceManager
{
    use \October\Rain\Support\Traits\Singleton;

    public $cache_remember_name      = '%_remember_store_device';
    public $is_remember_store_device = false;
    public $user, $data;

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function remember($cache_prefix = '')
    {
        $this->cache($cache_prefix);
        return $this;
    }

    public function isRemember($cache_prefix = '')
    {
        $this->cache($cache_prefix);
        return $this->is_remember_store_device;
    }

    public function cache($cache_prefix)
    {
        $cache_name                     = sprintf($this->cache_remember_name, $cache_prefix);
        $this->is_remember_store_device = (bool) Cache::get($cache_name);

        if(!Cache::has($cache_name)){
            Cache::put($cache_name, true, now()->addDay()->startOfDay());
        }
    }

    public function issue()
    {
        if($this->is_remember_store_device){
            return;
        }

        $device = Device::create([
            'uuid'             => data_get($this->data, 'uuid'),
            'platform'         => data_get($this->data, 'platform'),
            'user_id'          => isset($this->user) ? $this->user->id : data_get($this->data, 'user_id'),
            'push_token'       => data_get($this->data, 'push_token'),
            'version'          => data_get($this->data, 'version'),
            'name'             => data_get($this->data, 'name'),
            'platform_version' => data_get($this->data, 'platform_version'),
            'last_seen'        => now(),
            'latitude'         => data_get($this->data, 'latitude'),
            'longitude'        => data_get($this->data, 'longitude')
        ]);

        return $device;
    }
}