<?php namespace Hyyppa\Toxx\Format;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Exceptions\MissingFieldsException;
use Hyyppa\Toxx\Format\Ascii\Ascii;
use Hyyppa\Toxx\Format\CsiXml\CsiXml;
use Hyyppa\Toxx\Format\CSV\CSV;
use Hyyppa\Toxx\Format\Toa5\Toa5;
use Hyyppa\Toxx\Format\Toaci1\Toaci1;
use Hyyppa\Toxx\Format\Tob1\Tob1;
use Hyyppa\Toxx\Utils\FileSystem;
use Hyyppa\Toxx\Utils\Settings;

class DataFile
{

    /**
     * @param  string             $filename
     * @param  array|null         $field_names
     *
     * @param  SettingsInterface  $settings
     *
     * @return DataFileInterface
     */
    public static function load(
        string $filename,
        array $field_names = null,
        SettingsInterface $settings = null
    ) : DataFileInterface {
        FileSystem::AssertNotEmpty($filename);

        $settings = $settings ?? Settings::make();
        $class    = self::getFileFormatClass($filename);

        switch (true) {
            case ($class === CSV::class && ! $field_names) :
                throw new MissingFieldsException('CSV');
            case ($class === Ascii::class && ! $field_names) :
                throw new MissingFieldsException('ASCII');
            case ($field_names) :
                return new $class($filename, $field_names, $settings);
            default:
                return new $class($filename, $settings);
        }
    }


    /**
     * @param  string  $filename
     *
     * @return string
     * @throws UnknownFormatException
     */
    protected static function getFileFormatClass(string $filename) : string
    {
        $file       = fopen($filename, 'r');
        $first_line = rtrim(fgets($file));
        fclose($file);

        switch (true) {
            case(stristr($first_line, '"TOB1"')):
                return Tob1::class;
            case(stristr($first_line, '"TOA5"')):
                return Toa5::class;
            case(stristr($first_line, '"TOACI1"')):
                return Toaci1::class;
            case(substr_count($first_line, '<?xml') > 0):
                return CsiXml::class;
            case(substr_count($first_line, ',') > 2 && strpos($first_line, '"') === 0):
                return Ascii::class;
            case(substr_count($first_line, ',') > 2):
                return CSV::class;
            default:
                throw new UnknownFormatException($filename);
        }

    }

}
