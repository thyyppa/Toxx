<?php namespace Hyyppa\Toxx\Contracts\Format;

use Hyyppa\Toxx\Contracts\SettingsInterface;

/**
 * @property      $info
 * @property      $fields
 * @property      $units
 * @property      $processing
 * @property      $size
 * @property      $types
 * @property-read $count
 */
interface FileHeaderInterface
{

    /**
     * @return array|null
     */
    public function info() : ?array;


    /**
     * @return array|null
     */
    public function fields() : ?array;


    /**
     * @return array|null
     */
    public function units() : ?array;


    /**
     * @return array|null
     */
    public function processing() : ?array;


    /**
     * @return int
     */
    public function size() : int;


    /**
     * @return int|null
     */
    public function count() : ?int;


    /**
     * @param  array  $_info
     *
     * @return FileHeaderInterface
     */
    public function setInfo(array $_info) : FileHeaderInterface;


    /**
     * @param  array  $_units
     *
     * @return FileHeaderInterface
     */
    public function setUnits(array $_units) : FileHeaderInterface;


    /**
     * @param  array  $_fields
     *
     * @return FileHeaderInterface
     */
    public function setFields(array $_fields) : FileHeaderInterface;


    /**
     * @param  array  $_types
     *
     * @return FileHeaderInterface
     */
    public function setTypes(array $_types) : FileHeaderInterface;


    /**
     * @param  int  $size
     *
     * @return FileHeaderInterface
     */
    public function setSize(int $size) : FileHeaderInterface;


    /**
     * @param  array  $processing
     *
     * @return FileHeaderInterface
     */
    public function setProcessing(array $processing) : FileHeaderInterface;


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function unit(int $offset) : ?string;


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function field(int $offset) : ?string;


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function type(int $offset) : ?string;


    /**
     * @param  SettingsInterface|null  $settings
     *
     * @return SettingsInterface|null
     */
    public function settings(SettingsInterface $settings = null) : ?SettingsInterface;

}
