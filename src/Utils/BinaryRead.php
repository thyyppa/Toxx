<?php namespace Hyyppa\Toxx\Utils;

use Carbon\Carbon;
use const SEEK_CUR;
use const SEEK_END;

class BinaryRead
{

    /**
     * @param $file
     *
     * @return string
     */
    public static function string(&$file) : string
    {
        $string = '';

        while ( ! feof($file) && ($chr = fgetc($file)) !== "\x00") {
            $string .= $chr;
        }

        return $string;
    }


    /**
     * @param        $file
     * @param  bool  $big_endian
     *
     * @return int
     */
    public static function uint4(&$file, $big_endian = true) : int
    {
        $value = fread($file, 4);

        if (strlen($value) !== 4) {
            return 0;
        }

        if ($big_endian && Unpack::systemIsLittleEndian()) {
            $value = Unpack::flipEndianness($value);
        }

        return Unpack::UInt4($value);
    }


    /**
     * @param $file
     *
     * @return int
     */
    public static function byte(&$file) : int
    {
        return Unpack::UInt1(fread($file, 1));
    }


    /**
     * @param $file
     *
     * @return string
     */
    public static function char(&$file) : string
    {
        return fread($file, 1);
    }


    /**
     * @param          $file
     * @param  string  $method
     *
     * @return array
     */
    public static function array(&$file, $method = 'UInt4') : array
    {
        $output = [];

        while ( ! feof($file)) {
            $val = static::$method($file);

            if ( ! $val) {
                break;
            }

            $output[] = $val;
        }

        return $output;
    }


    /**
     * @param  resource  $file
     * @param  bool      $return_carbon
     *
     * @return Carbon|int
     */
    public static function NSec(&$file, $return_carbon = true)
    {
        if (strlen($value = fread($file, 8)) !== 8) {
            return 0;
        }

        return $return_carbon
            ? Unpack::NSec($value)
            : Unpack::NSec($value)->floatDiffInSeconds(
                self::beginningOfTime()
            );
    }


    /**
     * @param       $file
     * @param  int  $offset
     *
     * @return string
     */
    public static function peekNext(&$file, $offset = 1) : string
    {
        $byte = fread($file, $offset);

        fseek($file, -$offset, SEEK_CUR);

        return $byte;
    }


    /**
     * @param       $file
     * @param       $offset
     * @param  int  $length
     *
     * @return string
     */
    public static function peekOffset(&$file, $offset, $length = 1) : string
    {
        $ptr = ftell($file);

        fseek($file, $offset);
        $size = fread($file, $length);
        fseek($file, $ptr);

        return $size;
    }


    /**
     * @param $file
     */
    public static function seekNextNull(&$file) : void
    {
        while ( ! feof($file) && fgetc($file) !== "\x00") {
        }
    }


    /**
     * @param       $file
     * @param  int  $count
     */
    public static function skip(&$file, int $count = 1) : void
    {
        fseek($file, $count, SEEK_CUR);
    }


    /**
     * @param $file
     */
    public static function rewind(&$file) : void
    {
        rewind($file);
    }


    /**
     * @param $file
     *
     * @return int
     */
    public static function size(&$file) : int
    {
        $ptr = ftell($file);

        fseek($file, 0, SEEK_END);
        $size = ftell($file);
        fseek($file, $ptr);

        return $size;
    }


    /**
     * @return Carbon
     */
    protected static function beginningOfTime() : Carbon
    {
        return Carbon::parse('1990-01-01 00:00:00');
    }

}
