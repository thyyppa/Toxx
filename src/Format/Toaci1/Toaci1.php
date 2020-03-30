<?php namespace Hyyppa\Toxx\Format\Toaci1;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Format\Toa\BaseToa;
use SplFileObject;

class Toaci1 extends BaseToa
{

    /**
     * @param  SplFileObject  $file
     *
     * @return FileHeaderInterface
     */
    protected function parseHeader($file) : FileHeaderInterface
    {
        $header = new Toaci1FileHeader($this->_settings);

        $header->setInfo($file->fgetcsv());
        $header->setFields($file->fgetcsv());
        $header->setSize(2);

        return $header;
    }

}
