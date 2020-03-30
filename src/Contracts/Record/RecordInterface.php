<?php namespace Hyyppa\Toxx\Contracts\Record;

use Hyyppa\Toxx\Contracts\Reading\ReadingCollectionInterface;
use Hyyppa\Toxx\Contracts\Reading\ReadingInterface;

interface RecordInterface
{

    /**
     * @param  string  $field
     *
     * @return mixed|null
     */
    public function getRawValue(string $field);


    /**
     * @param $readings
     *
     * @return $this
     */
    public function removeReadings($readings) : self;


    /**
     * @return array
     */
    public function simple() : array;


    /**
     * @param  string  $field
     *
     * @return ReadingInterface
     */
    public function getReading(string $field) : ReadingInterface;


    /**
     * @param  array  $fields
     *
     * @return bool
     */
    public function hasReadings(array $fields = []) : bool;


    /**
     * @param  string  $needle
     * @param  array   $haystack
     *
     * @return bool
     */
    public function inArray(string $needle, array $haystack) : bool;


    /**
     * @return string
     */
    public function arrayKey() : string;


    /**
     * @param  bool  $raw
     *
     * @return string|null
     */
    public function getTimestamp($raw = false) : ?string;


    /**
     * @return array
     */
    public function array() : array;


    /**
     * @return ReadingCollectionInterface|ReadingInterface
     */
    public function readings() : ReadingCollectionInterface;


    /**
     * @return array
     */
    public function human() : array;


    /**
     * @param  bool  $raw
     *
     * @return int|string|null
     */
    public function getSeconds($raw = false);


    /**
     * @return RecordInterface
     */
    public function withHidden() : RecordInterface;


    /**
     * @param $fields
     *
     * @return array
     */
    public function only($fields) : array;


    /**
     * @return RecordInterface
     */
    public function setSeconds() : RecordInterface;


    /**
     * @param  string  $field
     *
     * @return bool
     */
    public function hasReading(string $field) : bool;


    /**
     * @param  int|null  $number
     *
     * @return RecordInterface
     */
    public function setRecordNumber(int $number = null) : RecordInterface;


    /**
     * @return ReadingCollectionInterface
     */
    public function withUnits() : ReadingCollectionInterface;


    /**
     * @param  string  $field
     *
     * @return mixed|null
     */
    public function getValue(string $field);


    /**
     * @return array
     */
    public function fields() : array;


    /**
     * @return array
     */
    public function arrayWithUnits() : array;


    /**
     * @param  bool  $raw
     *
     * @return int|null
     */
    public function getRecordNumber($raw = false) : ?int;

}
