<?php namespace Hyyppa\Toxx\Contracts\Reading;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;

interface ReadingInterface
{

    /**
     * @param  string  $_field
     *
     * @return $this
     */
    public function setField(string $_field) : ReadingInterface;


    /**
     * @return string
     */
    public function __toString() : string;


    /**
     * @return string|null
     */
    public function type() : ?string;


    /**
     * @return mixed
     */
    public function value();


    /**
     * @param  string|null  $_type
     *
     * @return ReadingInterface
     */
    public function setType(string $_type = null) : ReadingInterface;


    /**
     * @return string|null
     */
    public function processing() : ?string;


    /**
     * @return array
     */
    public function info() : array;


    /**
     * @param  string|null  $_processing
     *
     * @return ReadingInterface
     */
    public function setProcessing(string $_processing = null) : ReadingInterface;


    /**
     * @return string
     */
    public function originalField() : string;


    /**
     * @return bool
     */
    public function isHidden() : bool;


    /**
     * @return string
     */
    public function field() : string;


    /**
     * @param  string|null  $_unit
     *
     * @return ReadingInterface
     */
    public function setUnit(string $_unit = null) : ReadingInterface;


    /**
     * @return string|null
     */
    public function unit() : ?string;


    /**
     * @return FileHeaderInterface
     */
    public function header() : FileHeaderInterface;

}
