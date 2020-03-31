<?php namespace Octobro\Devices\APIControllers;

use Carbon\Carbon;
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
        return $this->respondWithCollection($this->getUser()->devices, new DeviceTransformer);
    }

    public function store()
    {
        $device = Device::firstOrNew([
            'uuid'     => $this->input->get('uuid'),
            'platform' => $this->input->get('platform'),
        ]);

        $device->fill([
            'user_id'           => $this->getUser()->id,
            'push_token'        => $this->input->get('push_token'),
            'version'           => $this->input->get('version'),
            'name'              => $this->input->get('name'),
            'platform_version'  => $this->input->get('platform_version'),
            'last_seen'         => Carbon::now(),
            'latitude'          => $this->input->get('latitude'),
            'longitude'         => $this->input->get('longitude')
        ]);

        $device->save();

        return $this->respondWithCollection($this->getUser()->devices, new DeviceTransformer);
    }

    public function destroy()
    {
        $device = Device::whereUuid($this->input->get('uuid'))->destroy();

        return $this->respondWithCollection($this->getUser()->devices, new DeviceTransformer);
    }
}