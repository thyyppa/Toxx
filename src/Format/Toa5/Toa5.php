<?php namespace Hyyppa\Toxx\Format\Toa5;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Format\Toa\BaseToa;
use SplFileObject;

class Toa5 extends BaseToa
{

    /**
     * @param  SplFileObject  $file
     *
     * @return FileHeaderInterface
     */
    protected function parseHeader($file) : FileHeaderInterface
    {
        $header = new Toa5FileHeader($this->_settings);

        $header->setInfo($file->fgetcsv());
        $header->setFields($file->fgetcsv());
        $header->setUnits($file->fgetcsv());
        $header->setProcessing($file->fgetcsv());
        $header->setSize(4);

        return $header;
    }

}
