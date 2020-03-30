<?php namespace Hyyppa\Toxx\Utils;

use Carbon\Carbon;
use Hyyppa\Toxx\Records\FieldFormat;
use Jawira\CaseConverter\Convert;

class Format extends Convert
{

    /**
     * @param  string  $string
     *
     * @return Convert
     */
    protected static function convert(string $string) : Convert
    {
        return new Convert($string);
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function camel(string $string) : string
    {
        return self::convert($string)->toCamel();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function pascal(string $string) : string
    {
        return self::convert($string)->toPascal();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function snake(string $string) : string
    {
        return self::convert($string)->toSnake();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function ada(string $string) : string
    {
        return self::convert($string)->toAda();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function macro(string $string) : string
    {
        return self::convert($string)->toMacro();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function kebab(string $string) : string
    {
        return self::convert($string)->toKebab();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function train(string $string) : string
    {
        return self::convert($string)->toTrain();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function cobol(string $string) : string
    {
        return self::convert($string)->toCobol();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function lower(string $string) : string
    {
        return self::convert($string)->toLower();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function upper(string $string) : string
    {
        return self::convert($string)->toUpper();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function title(string $string) : string
    {
        return self::convert($string)->toTitle();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function sentence(string $string) : string
    {
        return self::convert($string)->toSentence();
    }


    /**
     * @param  string  $string
     *
     * @return string
     */
    public static function dot(string $string) : string
    {
        return self::convert($string)->toDot();
    }


    /**
     * @param  string       $string
     *
     * @param  string|null  $format
     *
     * @return string
     */
    public static function carbon(string $string, string $format) : string
    {
        $format = explode('|', $format);

        if (count($format) === 1) {
            return Carbon::parse($string);
        }

        return Carbon::parse($string)->format($format[ 1 ]);

    }


    /**
     * @param  string                $string
     * @param  string|callable|null  $format
     *
     * @return string
     */
    public static function text(string $string, $format = null) : string
    {
        if ( ! $format) {
            return $string;
        }

        if (is_callable($format)) {
            return self::call($format, $string);
        }

        if (stristr($format, FieldFormat::Carbon) !== false) {
            return self::carbon($string, $format);
        }

        if ( ! stristr(FieldFormat::Cases, $format)) {
            return $string;
        }

        $method = strtolower($format);

        return self::$method($string);
    }


    /**
     * @param  callable  $callback
     * @param  string    $string
     *
     * @return string
     */
    public static function call(callable $callback, string $string = null) : string
    {
        return $callback($string);
    }

}
