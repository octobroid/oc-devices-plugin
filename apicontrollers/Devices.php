<?php namespace Octobro\Devices\APIControllers;

use Event, Queue;
use Octobro\Devices\Models\Device;
use Octobro\API\Classes\ApiController;
use Octobro\Devices\Transformers\DeviceTransformer;
use Octobro\Devices\Classes\DeviceManager;

/**
 *
 */
class Devices extends ApiController
{
    public function index()
    {
        $user    = $this->getUser();
        $devices = $user->devices()->remember(1)->get();

        return $this->respondWithCollection($devices, new DeviceTransformer);
    }

    public function store()
    {
        $user = $this->getUser();

        if(DeviceManager::instance()->isRemember($user->id)){
            $devices = $user->devices()->remember(2)->get();
            return $this->respondWithCollection($devices, new DeviceTransformer);
        }
        
        $device = Device::where('user_id', $user->id)
        ->orderBy('updated_at', 'desc')
        ->limit(1)
        ->first();

        if(!$device){
            $device = Device::create([
                'user_id'          => $user->id,
                'push_token'       => $this->input->get('push_token'),
                'version'          => $this->input->get('version'),
                'name'             => $this->input->get('name'),
                'platform_version' => $this->input->get('platform_version'),
                'last_seen'        => now(),
                'latitude'         => $this->input->get('latitude'),
                'longitude'        => $this->input->get('longitude')
            ]);
        }else{
            $device->fill([
                'user_id'          => $user->id,
                'push_token'       => $this->input->get('push_token'),
                'version'          => $this->input->get('version'),
                'name'             => $this->input->get('name'),
                'platform_version' => $this->input->get('platform_version'),
                'last_seen'        => now(),
                'latitude'         => $this->input->get('latitude'),
                'longitude'        => $this->input->get('longitude')
            ]);

            $device->save();
        }

        $devices = $user->devices()->remember(2)->get();
        return $this->respondWithCollection($devices, new DeviceTransformer);
    }

    public function storeV2()
    {
        $user       = $this->getUser();
        $data       = [
            'user_id'          => $user->id,
            'uuid'             => $this->input->get('uuid'),
            'platform'         => $this->input->get('platform'),
            'push_token'       => $this->input->get('push_token'),
            'version'          => $this->input->get('version'),
            'name'             => $this->input->get('name'),
            'platform_version' => $this->input->get('platform_version'),
            'latitude'         => $this->input->get('latitude'),
            'longitude'        => $this->input->get('longitude')
        ];

        // To make it works, please add DeviceTrait into your User Model
        if(!in_array($data['push_token'], (array) $user->device_tokens)){
            Queue::push('Octobro\Devices\Jobs\DeviceJob', $data);
        }
        
        Event::fire('Octobro.Devices.StoreV2', [$user, $data]);

        return response()->json([
            'status'  => 'Success',
            'message' => 'Device Successfully Saved'
        ]);
    }

    public function destroy($uuid)
    {
        $user    = $this->getUser();
        $device  = Device::whereUuid($uuid)->delete();
        $devices = $user->devices()->remember(2)->get();

        return $this->respondWithCollection($devices, new DeviceTransformer);
    }
}
