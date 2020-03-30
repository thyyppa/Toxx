<?php namespace Hyyppa\Toxx\Format\General;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Traits\LazyAccessor;
use Jawira\CaseConverter\CaseConverterException;
use Jawira\CaseConverter\Convert;

class FileHeader implements FileHeaderInterface
{

    use LazyAccessor;

    /**
     * @var
     */
    protected $_info;

    /**
     * @var
     */
    protected $_fields;

    /**
     * @var
     */
    protected $_units;

    /**
     * @var
     */
    protected $_processing;

    /**
     * @var
     */
    protected $_size;

    /**
     * @var
     */
    protected $_types;

    /**
     * @var SettingsInterface|null
     */
    protected $_settings;


    public function __construct(SettingsInterface $settings = null)
    {
        $this->_settings = $settings;
    }


    /**
     * @param  array  $_info
     *
     * @return FileHeaderInterface
     */
    public function setInfo(array $_info) : FileHeaderInterface
    {
        $this->_info = $_info;

        return $this;
    }


    /**
     * @return array|null
     */
    public function info() : ?array
    {
        return $this->_info;
    }


    /**
     * @param  array  $_fields
     *
     * @return FileHeaderInterface
     */
    public function setFields(array $_fields) : FileHeaderInterface
    {
        $this->_fields = $_fields;

        return $this;
    }


    /**
     * @return array|null
     */
    public function fields() : ?array
    {
        return $this->_fields;
    }


    /**
     * @param  array  $_units
     *
     * @return FileHeaderInterface
     */
    public function setUnits(array $_units) : FileHeaderInterface
    {
        $this->_units = $_units;

        return $this;
    }


    /**
     * @return array|null
     */
    public function units() : ?array
    {
        return $this->_units;
    }


    /**
     * @param  array  $processing
     *
     * @return FileHeaderInterface
     */
    public function setProcessing(array $processing) : FileHeaderInterface
    {
        $this->_processing = $processing;

        return $this;
    }


    /**
     * @return array|null
     */
    public function processing() : ?array
    {
        return $this->_processing;
    }


    /**
     * @param  int  $size
     *
     * @return FileHeaderInterface
     */
    public function setSize(int $size) : FileHeaderInterface
    {
        $this->_size = $size;

        return $this;
    }


    /**
     * @return int
     */
    public function size() : int
    {
        return $this->_size;
    }


    /**
     * @return int|null
     */
    public function count() : ?int
    {
        return null;
    }


    /**
     * @param  array  $_types
     *
     * @return FileHeaderInterface
     */
    public function setTypes(array $_types) : FileHeaderInterface
    {
        $this->_types = $_types;

        return $this;
    }


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function type(int $offset) : ?string
    {
        if ( ! is_array($this->_types)) {
            return null;
        }

        return $this->_types[ $offset ];
    }


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function field(int $offset) : ?string
    {
        if ( ! is_array($this->_fields)) {
            return null;
        }

        return $this->_fields[ $offset ];
    }


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function unit(int $offset) : ?string
    {
        if ( ! is_array($this->_units)) {
            return null;
        }

        return $this->_units[ $offset ];
    }


    /**
     * @param  int  $offset
     *
     * @return string|null
     */
    public function process(int $offset) : ?string
    {
        if ( ! is_array($this->_processing)) {
            return null;
        }

        return $this->_processing[ $offset ];
    }


    /**
     * @inheritDoc
     */
    public function setSettings() : ?SettingsInterface
    {
        return $this->_settings;
    }


    /**
     * @param  SettingsInterface|null  $settings
     *
     * @return SettingsInterface|null
     */
    public function settings(SettingsInterface $settings = null) : ?SettingsInterface
    {
        if ($settings) {
            $this->_settings = $settings;
        }

        return $this->_settings;
    }


    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $name = '_'.$name;

        if (property_exists($this, $name) && $this->$name !== null) {
            return true;
        }

        return false;
    }


    /**
     * @param $name
     * @param $value
     *
     * @return mixed|FileHeaderInterface
     * @throws CaseConverterException
     */
    public function __set($name, $value)
    {
        $method = (new Convert('set_'.$name))->toCamel();

        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        $this->$name = $value;
    }


}
