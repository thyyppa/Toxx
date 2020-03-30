<?php namespace Hyyppa\Toxx\Utils;

use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Exceptions\SettingsException;
use Hyyppa\Toxx\Records\FieldFormat;
use Jawira\CaseConverter\Convert;
use const JSON_PRETTY_PRINT;

class Settings implements SettingsInterface
{

    /**
     * @var array
     */
    protected $_precision = [];

    /**
     * @var array
     */
    protected $_alias = [];

    /**
     * @var array
     */
    protected $_hidden = [];

    /**
     * @var array
     */
    protected $_disabled_units = [];

    /**
     * @var array
     */
    protected $_field_format = [];

    /**
     * @var array
     */
    protected $_transforms = [];

    /**
     * @var string|callable
     */
    protected $_key = 'seconds';

    /**
     * @var bool
     */
    protected $_remove_suffix = false;


    /**
     * @param  null  $config
     */
    public function __construct($config = null)
    {
        if (is_array($config)) {
            return $this->fromArray($config);
        }

        if (is_string($config) && strpos($config, '{') !== false) {
            return $this->fromJson($config);
        }
    }


    /**
     * @param  array  $config
     *
     * @return SettingsInterface
     */
    public function fromArray(array $config) : SettingsInterface
    {
        foreach ($config as $k => $v) {
            $this->$k = $v;
        }

        return $this;
    }


    /**
     * @param  string  $config
     *
     * @return SettingsInterface
     */
    public function fromJson(string $config) : SettingsInterface
    {
        $config = json_decode($config, true);

        return $this->fromArray($config);
    }


    /**
     * @return array
     */
    public function toArray() : array
    {
        $props  = (array) $this;
        $output = [];

        foreach ($props as $k => $v) {
            $output[ substr($k, 4) ] = $v;
        }

        return $output;
    }


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function toJson(int $options = JSON_PRETTY_PRINT) : string
    {
        return json_encode($this->toArray(), $options);
    }


    /**
     * @param        $type
     * @param  null  $index
     *
     * @return mixed
     */
    public function get($type, $index = null)
    {
        if ( ! $index || ! is_array($this->$type())) {
            return $this->__get($type);
        }

        return $this->__get($type)[ $index ] ?? null;
    }


    /**
     * @param        $type
     * @param  null  $index
     *
     * @return bool
     */
    public function has($type, $index = null) : bool
    {
        return isset($this->$type()[ $index ]) ? true : false;
    }


    /**
     * @param $params
     *
     * @return SettingsInterface|array
     */
    public function hidden($params = null)
    {
        return $this->getOrSet(__FUNCTION__, $params);
    }


    /**
     * @param $remove
     *
     * @return SettingsInterface|bool
     */
    public function removeSuffix(bool $remove = true)
    {
        return $this->getOrSet('remove_suffix', $remove);
    }


    /**
     * @param  null  $params
     *
     * @return mixed
     */
    public function disabledUnits($params = null)
    {
        return $this->getOrSet('disabled_units', $params);
    }


    /**
     * @param $field
     *
     * @return SettingsInterface|array
     */
    public function hide($field)
    {
        if ( ! is_array($field)) {
            $field = [$field];
        }

        return $this->hidden(
            array_merge($this->_hidden, $field)
        );
    }


    /**
     * @param $params
     *
     * @return SettingsInterface|array
     */
    public function alias(array $params = null)
    {
        return $this->getOrSet(__FUNCTION__, $params);
    }


    /**
     * @param $params
     *
     * @return SettingsInterface|array
     */
    public function transforms(array $params = null)
    {
        return $this->getOrSet(__FUNCTION__, $params);
    }


    /**
     * @param  string|callable|null  $format
     *
     * @return SettingsInterface|string|callable
     */
    public function key($format = null)
    {
        return $this->getOrSet(__FUNCTION__, $format);
    }


    /**
     * @param  null  $params
     *
     * @return mixed
     */
    public function fieldFormat($params = null)
    {
        return $this->getOrSet('field_format', $params);
    }


    /**
     * @param $params
     *
     * @return SettingsInterface|array
     */
    public function precision($params = null)
    {
        return $this->getOrSet(__FUNCTION__, $params);
    }


    /**
     * @return SettingsInterface
     */
    public function simplify() : SettingsInterface
    {
        return $this
            ->precision([
                'RECORD'  => 0,
                'SECONDS' => 0,
            ])
            ->hidden([
                'SECONDS',
                'RECORD',
                'TABLE',
                'NANOSECONDS',
                'YEAR',
                'DAY',
                'TIME',
            ])
            ->disabledUnits([
                'RECORD',
                'SECONDS',
                'TIMESTAMP',
                'NANOSECONDS',
            ])
            ->fieldFormat(
                FieldFormat::Snake
            );
    }


    /**
     * @param        $property
     * @param  null  $value
     *
     * @return mixed
     */
    protected function getOrSet($property, $value = null)
    {
        if ($value === null) {
            return $this->{'_'.$property};
        }

        $this->{'_'.$property} = $value;

        return $this;
    }


    /**
     * @param  null  $config
     *
     * @return SettingsInterface
     */
    public static function make($config = null) : SettingsInterface
    {
        return new self($config);
    }


    /**
     * @return SettingsInterface
     */
    public static function simple() : SettingsInterface
    {
        return static::make()->simplify();
    }


    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $property = (new Convert($name))->toSnake();

        if ($property[ 0 ] !== '_') {
            $property = '_'.$property;
        }

        if ( ! property_exists($this, $property)) {
            throw new SettingsException('Setting `'.$name.'` does not exist.');
        }

        return $this->$property;
    }


    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        $method = (new Convert($name))->toCamel();

        if ( ! method_exists($this, $method)) {
            throw new SettingsException('Setting `'.$name.'` does not exist.');
        }

        return $this->$method($value);
    }


}
