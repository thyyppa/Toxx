<?php namespace Hyyppa\Toxx\Format\Ascii;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Format\CSV\CSV;
use Hyyppa\Toxx\Format\CSV\CSVFileHeader;

class Ascii extends CSV
{


    /**
     * @param  array  $fields
     *
     * @return FileHeaderInterface
     */
    protected function parseHeader(array $fields) : FileHeaderInterface
    {
        $header = new CSVFileHeader($this->_settings);

        array_unshift($fields, 'TIMESTAMP', 'RECORD');
        $header->setFields($fields);
        $header->setSize(0);

        return $header;
    }

}
