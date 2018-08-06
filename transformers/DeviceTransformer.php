<?php namespace Octobro\Devices\Transformers;

use Octobro\Devices\Models\Device;
use Octobro\API\Classes\Transformer;

class DeviceTransformer extends Transformer
{
    public function data(Device $device)
    {
        return [
            'name'             => $device->name,
            'uuid'             => $device->uuid,
            'push_token'       => $device->push_token,
            'platform'         => $device->platform,
            'platform_version' => $device->platform_version,
            'version'          => $device->version,
            'last_seen'        => date($device->last_seen),
        ];
    }
}