<?php namespace Hyyppa\Toxx\Contracts;

use const JSON_PRETTY_PRINT;

interface JsonAndArrayOutput
{

    /**
     * @param  int  $options
     *
     * @return string
     */
    public function json($options = JSON_PRETTY_PRINT) : string;


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
     * @return array
     */
    public function array() : array;


    /**
     * @return array
     */
    public function arrayWithUnits() : array;


    /**
     * @return array
     */
    public function arrayWithHidden() : array;

}
