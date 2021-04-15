<?php namespace Octobro\Devices\Jobs;

use DB;
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
            
            $device = Device::firstOrNew([
                'uuid'     => data_get($data, 'uuid'),
                'platform' => data_get($data, 'platform'),
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

            DB::commit();
        } catch (ApplicationException $th) {
            DB::rollback();
            throw $th;
        }

        $job->delete();
    }
}