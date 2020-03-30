<?php namespace Hyyppa\Toxx\Contracts;

use const JSON_PRETTY_PRINT;

/**
 * @property array        $hidden
 * @property array        $disabled_units
 * @property array        $disabledUnits
 * @property array        $alias
 * @property array        $transforms
 * @property array|string $field_format
 * @property array|string $fieldFormat
 * @property array        $precision
 */
interface SettingsInterface
{

    /**
     * @param  array|string|json  $config
     *
     * @return SettingsInterface
     */
    public static function make($config = null) : SettingsInterface;


    /**
     * @return SettingsInterface
     */
    public static function simple() : SettingsInterface;


    /**
     * @param        $type
     * @param  null  $index
     *
     * @return mixed
     */
    public function get($type, $index = null);


    /**
     * @param        $type
     * @param  null  $index
     *
     * @return bool
     */
    public function has($type, $index = null) : bool;


    /**
     * @param $params
     *
     * @return array|SettingsInterface
     */
    public function hidden($params = null);


    /**
     * @param  null  $params
     *
     * @return array|SettingsInterface
     */
    public function disabledUnits($params = null);


    /**
     * @param $remove
     *
     * @return bool|SettingsInterface
     */
    public function removeSuffix(bool $remove = true);


    /**
     * @param $field
     *
     * @return array|SettingsInterface
     */
    public function hide($field);


    /**
     * @param $params
     *
     * @return array|SettingsInterface
     */
    public function alias(array $params = null);


    /**
     * @param $params
     *
     * @return array|SettingsInterface
     */
    public function transforms(array $params = null);


    /**
     * @param  string|callable|null  $format
     *
     * @return string|callable|SettingsInterface
     */
    public function key($format = null);


    /**
     * @param  null  $params
     *
     * @return mixed|SettingsInterface
     */
    public function fieldFormat($params = null);


    /**
     * @param $params
     *
     * @return array|SettingsInterface
     */
    public function precision($params = null);


    /**
     * @return SettingsInterface
     */
    public function simplify() : SettingsInterface;


    /**
     * @param  array  $config
     *
     * @return SettingsInterface
     */
    public function fromArray(array $config) : SettingsInterface;


    /**
     * @param  string  $config
     *
     * @return SettingsInterface
     */
    public function fromJson(string $config) : SettingsInterface;


    /**
     * @return array
     */
    public function toArray() : array;


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function toJson(int $options = JSON_PRETTY_PRINT) : string;

}
