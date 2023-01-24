<?php namespace Octobro\Devices\Jobs;

use Cache, DB, Event;
use Octobro\Devices\Models\Device;
use Octobro\Devices\Classes\DeviceManager;

/**
 *
 */
class DeviceJob
{

    public function fire($job, $data)
    {
        try {
            if(DeviceManager::instance()->isRemember(data_get($data, 'user_id'))){
                $job->delete();
                return;
            }

            DB::beginTransaction();
            
            // Clean up existing tokens
            Device::wherePushToken(data_get($data, 'push_token'))->delete();

            $device = Device::where('user_id', data_get($data, 'user_id'))
            ->orderBy('updated_at', 'desc')
            ->limit(1)
            ->first();

            if(!$device){
                $device = Device::create([
                    'uuid'              => data_get($data, 'uuid'),
                    'platform'          => data_get($data, 'platform'),
                    'user_id'           => data_get($data, 'user_id'),
                    'push_token'        => data_get($data, 'push_token'),
                    'version'           => data_get($data, 'version'),
                    'name'              => data_get($data, 'name'),
                    'platform_version'  => data_get($data, 'platform_version'),
                    'last_seen'         => now(),
                    'latitude'          => data_get($data, 'latitude'),
                    'longitude'         => data_get($data, 'longitude')
                ]);
            }else{
                $device->fill([
                    'uuid'              => data_get($data, 'uuid'),
                    'platform'          => data_get($data, 'platform'),
                    'user_id'           => data_get($data, 'user_id'),
                    'push_token'        => data_get($data, 'push_token'),
                    'version'           => data_get($data, 'version'),
                    'name'              => data_get($data, 'name'),
                    'platform_version'  => data_get($data, 'platform_version'),
                    'last_seen'         => now(),
                    'latitude'          => data_get($data, 'latitude'),
                    'longitude'         => data_get($data, 'longitude')
                ]);
                
                $device->save();
            }

            // Alternative way to get push token for user relation
            $this->addDeviceToCache($data, $device);
            
            Event::fire('Octobro.Devices.Job', [$device->user, $data]);

            DB::commit();
        } catch (ApplicationException $th) {
            DB::rollback();
            throw $th;
        }

        $job->delete();
    }

    protected function addDeviceToCache($data, $device)
    {
        $cacheName  = sprintf('%s_token_devices', data_get($data, 'user_id'));
        $tokens     = Cache::get($cacheName, []);

        array_push($tokens, $device->push_token);

        Cache::put($cacheName, $tokens, 255);
    }
}
