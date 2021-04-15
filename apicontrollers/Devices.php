<?php namespace Octobro\Devices\APIControllers;

use Queue;
use Octobro\Devices\Models\Device;
use Octobro\API\Classes\ApiController;
use Octobro\Devices\Transformers\DeviceTransformer;

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
        $user   = $this->getUser();
        $device = Device::firstOrNew([
            'uuid'     => $this->input->get('uuid'),
            'platform' => $this->input->get('platform'),
        ]);

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

        Queue::push('Octobro\Devices\Jobs\DeviceJob', $data, 'low');

        $devices = $user->devices()->remember(2)->get();

        return $this->respondWithCollection($devices, new DeviceTransformer);
    }

    public function destroy()
    {
        $user    = $this->getUser();
        $device  = Device::whereUuid($this->input->get('uuid'))->destroy();
        $devices = $user->devices()->remember(2)->get();

        return $this->respondWithCollection($devices, new DeviceTransformer);
    }
}