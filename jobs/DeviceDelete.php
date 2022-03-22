<?php namespace Octobro\Devices\Jobs;

use Cache, DB, Event;
use Octobro\Devices\Models\Device;

/**
 *
 */
class DeviceDelete
{

    public function fire($job, $data)
    {
        try {
            DB::beginTransaction();

            Device::where('push_token', data_get($data, 'push_token'))->delete();

            DB::commit();
        } catch (\ApplicationException $th) {
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
