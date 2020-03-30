<?php namespace Hyyppa\Toxx\Contracts\Record;

use const JSON_PRETTY_PRINT;

interface JsonableRecordInterface
{

    /**
     * @param  int  $options
     *
     * @return string
     */
    public function jsonWithUnits($options = JSON_PRETTY_PRINT) : string;


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function jsonWithHidden($options = JSON_PRETTY_PRINT) : string;


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function json($options = JSON_PRETTY_PRINT) : string;


}
