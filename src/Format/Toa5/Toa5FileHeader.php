<?php namespace Hyyppa\Toxx\Format\Toa5;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Format\General\FileHeader;

class Toa5FileHeader extends FileHeader
{

    /**
     * @param  array  $info
     *
     * @return FileHeaderInterface
     */
    public function setInfo(array $info) : FileHeaderInterface
    {
        return parent::setInfo(
            array_combine([
                'format',
                'station',
                'datalogger',
                'serial_number',
                'os_version',
                'dld_name',
                'dld_signature',
                'table',
            ], $info)
        );
    }

}
