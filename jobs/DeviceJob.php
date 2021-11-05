<?php namespace Octobro\Devices\Jobs;

use Cache, DB, Event;
use Octobro\Devices\Models\Device;

/**
 *
 */
class DeviceJob
{

    public function fire($job, $data)
    {
        try {
            DB::beginTransaction();
            
            // Clean up existing tokens
            Device::wherePushToken(data_get($data, 'push_token'))->delete();

            $device = Device::firstOrNew([
                'uuid'       => data_get($data, 'uuid'),
                'platform'   => data_get($data, 'platform'),
                'push_token' => data_get($data, 'push_token')
            ]);
    
            $device->fill([
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
