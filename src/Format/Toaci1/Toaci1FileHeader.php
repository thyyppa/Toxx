<?php namespace Hyyppa\Toxx\Format\Toaci1;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Format\General\FileHeader;

class Toaci1FileHeader extends FileHeader
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
                'table',
            ], $info)
        );
    }

}
