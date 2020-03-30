<?php namespace Hyyppa\Toxx;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Contracts\ToxxInterface;
use Hyyppa\Toxx\Exceptions\TdfException;
use Hyyppa\Toxx\Exceptions\ToxxException;
use Hyyppa\Toxx\Format\DataFile;
use Hyyppa\Toxx\TableDefinition\TDF;
use Hyyppa\Toxx\Utils\Settings;

class Toxx implements ToxxInterface
{

    /**
     * @var string
     */
    protected static $_tdf;

    /**
     * @var string
     */
    protected static $_table;

    /**
     * @var array
     */
    protected static $_fields;

    /**
     * @var SettingsInterface
     */
    protected static $_settings;

    /**
     * @var SettingsInterface
     */
    protected static $_default_settings;

    /**
     * @var ToxxInterface
     */
    protected static $_instance;


    /**
     * @return ToxxInterface
     */
    protected static function getInstance() : ToxxInterface
    {
        if ( ! self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * @param  string       $filename
     * @param  string|null  $table
     *
     * @return ToxxInterface
     */
    public function tdf(string $filename, string $table = null) : ToxxInterface
    {
        self::setTdf($filename);

        if ($table) {
            self::setTable($table);
        }

        return self::getInstance();
    }


    /**
     * @param  string  $table
     *
     * @return ToxxInterface
     */
    public function table(string $table) : ToxxInterface
    {
        self::setTable($table);

        return self::getInstance();
    }


    /**
     * @param  array  $fields
     *
     * @return ToxxInterface
     */
    public function fields(array $fields) : ToxxInterface
    {
        self::setFields($fields);

        return self::getInstance();
    }


    /**
     * @param  mixed  ...$params
     *
     * @return DataFileInterface
     */
    public static function load(...$params) : DataFileInterface
    {
        $filename = null;
        $fields   = null;

        foreach ($params as $k => $param) {
            switch (true) {
                case(is_array($param)):
                    self::setFields($param);
                    unset($params[ $k ]);
                    break;
                case (is_a($param, Settings::class)):
                    self::setSettings($param);
                    unset($params[ $k ]);
                    break;
                case (stristr($param, '.tdf')):
                    self::setTdf($param);
                    unset($params[ $k ]);
                    break;
                case (strpos($param, '.') === false):
                    self::setTable($param);
                    unset($params[ $k ]);
                    break;
            }
        }

        if (count($params) === 1) {
            $filename = array_shift($params);
        }

        if (count($params) > 1) {
            throw new ToxxException(
                'There was a problem parsing the parameters passed to load(), try explicitly defining the parameters using ->table(), ->tdf(), ->fields(), etc'
            );
        }

        if (self::$_tdf) {
            if (self::$_table === null) {
                throw new TdfException(
                    'A table must be defined when using a TDF. Try Toxn::tdf(\'filename.tdf\')->table(\'TableName\')->...'
                );
            }

            self::setFields(
                TDF::load(self::$_tdf)->table(self::$_table)->fieldNames()
            );
        }

        $fields   = self::$_fields;
        $settings = self::getSettings();
        self::destruct();

        return DataFile::load($filename, $fields, $settings);
    }


    /**
     * @param  mixed  $_tdf
     *
     * @return ToxxInterface
     */
    protected static function setTdf($_tdf) : ToxxInterface
    {
        self::$_tdf = $_tdf;

        return self::getInstance();
    }


    /**
     * @param  mixed  $_table
     *
     * @return ToxxInterface
     */
    protected static function setTable($_table) : ToxxInterface
    {
        self::$_table = $_table;

        return self::getInstance();
    }


    /**
     * @param  array  $_fields
     *
     * @return ToxxInterface
     */
    protected static function setFields(array $_fields) : ToxxInterface
    {
        self::$_fields = $_fields;

        return self::getInstance();
    }


    /**
     * @param  SettingsInterface  $settings
     *
     * @return ToxxInterface
     */
    protected static function setSettings(SettingsInterface $settings) : ToxxInterface
    {
        self::$_settings = $settings;

        return self::getInstance();
    }


    /**
     * @param  SettingsInterface  $default_settings
     *
     * @return ToxxInterface
     */
    public static function defaultSettings(SettingsInterface $default_settings) : ToxxInterface
    {
        self::$_default_settings = $default_settings;

        return self::getInstance();
    }


    /**
     * @return SettingsInterface
     */
    protected static function getSettings() : SettingsInterface
    {
        if (self::$_settings) {
            return clone self::$_settings;
        }

        if (self::$_default_settings) {
            return clone self::$_default_settings;
        }

        return Settings::make();
    }


    /**
     *
     */
    protected static function destruct() : void
    {
        self::$_tdf      = null;
        self::$_table    = null;
        self::$_fields   = null;
        self::$_instance = null;
        self::$_settings = null;
    }

}
