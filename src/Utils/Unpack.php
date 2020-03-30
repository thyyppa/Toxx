<?php namespace Hyyppa\Toxx\Utils;

use Carbon\Carbon;

class Unpack
{

    /**
     * Two byte floating point with a single sign bit, two-bit negative decimal exponent,
     * and a 13-bit mantissa
     *
     * [ seem mmmm ] [ mmmm mmmm ]
     *
     * @param  string  $value
     *
     * @return float
     */
    public static function FP2($value) : float
    {
        if (self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        $value    = unpack('s', $value)[ 1 ];
        $sign     = (0x8000 & $value) >> 15;
        $exponent = (0x6000 & $value) >> 13;
        $mantissa = (0x1FFF & $value);

        return $mantissa * pow(10, -$exponent) * ($sign === 0 ? 1 : -1);
    }


    /**
     * Three byte floating point with a single sign bit, a seven-bit base-two exponent,
     * and a 16-bit mantissa. Probably. (this is an undefined format and likely wrong)
     *
     * [ seee eeee ] [ mmmm mmmm ] [ mmmm mmmm ]
     *
     * NOTE ------------------------------------------------------------------------------ NOTE
     *
     *    FP3/CSIFS3 IS A BRIEFLY REFERENCED DATATYPE IN CAMPBELL SCIENTIFIC DOCUMENTATION,
     *    THE LOGGERNET SERVER SDK DOCUMENTATION IN PARTICULAR, BUT IT IS NEVER DEFINED.
     *    THIS METHOD IS INCLUDED FOR COMPREHENSIVITY AND IS ONLY A BEST-GUESS AS TO
     *    HOW THIS SEEMINGLY-UNUSED FORMAT IS STORED. IT IS VERY LIKELY INCORRECT.
     *
     *    IF YOU HAVE ANY INFORMATION ON THE FP3 FORMAT PLEASE FEEL FREE TO CONTRIBUTE.
     *
     * NOTE ------------------------------------------------------------------------------ NOTE
     *
     * @param  string  $value
     *
     * @return float
     */
    public static function FP3($value) : float
    {
        if (self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        $value    = unpack('C3', $value)[ 1 ];
        $sign     = (0x80 & $value[ 3 ]) >> 7;
        $exponent = (0x7f & $value[ 3 ]);
        $mantissa = (0xFF & $value[ 2 ]) << 8;
        $mantissa |= $value[ 1 ];

        return $mantissa * pow(2, $exponent) * ($sign === 0 ? 1 : -1);
    }


    /**
     * Four byte floating point with a single sign bit, a seven-bit base-two exponent,
     * and a 24-bit mantissa
     *
     * todo: untested (legacy format?)
     *
     * [ seee eeee ] [ mmmm mmmm ] [ mmmm mmmm ] [ mmmm mmmm ]
     *
     * @param  string  $value
     *
     * @return float
     */
    public static function FP4($value) : float
    {
        if (self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        $value    = unpack('l', $value)[ 1 ];
        $sign     = (0x80000000 & $value) >> 31;
        $exponent = (0x7F000000 & $value) >> 24;
        $mantissa = (0x00FFFFFF & $value);

        return $mantissa * pow(2, $exponent) * ($sign === 0 ? 1 : -1);
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return float
     */
    public static function IEEE4($value, $use_little_endian = false) : float
    {
        return unpack($use_little_endian ? 'G' : 'g', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return float
     */
    public static function IEEE8($value, $use_little_endian = false) : float
    {
        return unpack($use_little_endian ? 'E' : 'e', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     *
     * @return int
     */
    public static function UInt1($value) : int
    {
        return unpack('C1', $value)[ 1 ];
    }


    /**
     * @param        $value
     *
     * @param  bool  $use_little_endian
     *
     * @return int
     */
    public static function UInt2($value, $use_little_endian = false) : int
    {
        return unpack($use_little_endian ? 'n' : 'v', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return int
     */
    public static function UInt4($value, $use_little_endian = false) : int
    {
        return unpack($use_little_endian ? 'N' : 'V', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return int
     */
    public static function UInt6($value, $use_little_endian = false) : int
    {
        if ($use_little_endian && ! self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        $byte = unpack('C6', $value);

        return
            ($byte[ $i = 6 ] << $s = 0) |
            ($byte[ --$i ] << $s += 8) |
            ($byte[ --$i ] << $s += 8) |
            ($byte[ --$i ] << $s += 8) |
            ($byte[ --$i ] << $s += 8) |
            ($byte[ --$i ] << $s + 8);
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return int
     */
    public static function UInt8($value, $use_little_endian = false) : int
    {
        return unpack($use_little_endian ? 'J' : 'P', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     *
     * @return int
     */
    public static function Int1($value) : int
    {
        return unpack('c1', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return int
     */
    public static function Int2($value, $use_little_endian = false) : int
    {
        if ($use_little_endian && ! self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        return unpack('s', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return int
     */
    public static function Int4($value, $use_little_endian = false) : int
    {
        if ($use_little_endian && ! self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        return unpack('l', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return int
     */
    public static function Int8($value, $use_little_endian = false) : int
    {
        if ($use_little_endian && ! self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        return unpack('q', $value)[ 1 ];
    }


    /**
     * @param  string  $value
     *
     * @return string
     */
    public static function Ascii($value) : string
    {
        $value = explode("\x00", $value)[ 0 ];
        $value = unpack('C'.strlen($value), $value);

        return implode('', array_map('chr', $value));
    }


    /**
     * @param  string  $value
     *
     * @return bool
     */
    public static function Boolean($value) : bool
    {
        return unpack('C', $value)[ 1 ] === 0xff;
    }


    /**
     * @param  string  $value
     *
     * @return bool
     */
    public static function Bool1($value) : bool
    {
        return unpack('C', $value)[ 1 ] !== 0;
    }


    /**
     * @param  string  $value
     *
     * @return bool
     */
    public static function Bool2($value) : bool
    {
        return self::UInt2($value) !== 0;
    }


    /**
     * @param  string  $value
     *
     * @return bool
     */
    public static function Bool4($value) : bool
    {
        return self::UInt4($value) !== 0;
    }


    /**
     * @param  string  $value
     *
     * @return array
     */
    public static function Flags($value) : array
    {
        $b = unpack('C', $value)[ 1 ];

        return [
            ($b & (1 << $i = 0)) > 0,
            ($b & (1 << ++$i)) > 0,
            ($b & (1 << ++$i)) > 0,
            ($b & (1 << ++$i)) > 0,
            ($b & (1 << ++$i)) > 0,
            ($b & (1 << ++$i)) > 0,
            ($b & (1 << ++$i)) > 0,
            ($b & (1 << ++$i)) > 0,
        ];
    }


    /**
     * @param  string  $value
     *
     * @return Carbon
     */
    public static function Carbon($value) : Carbon
    {
        return Carbon::parse($value);
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return Carbon
     */
    public static function Seconds($value, $use_little_endian = false) : Carbon
    {
        return Carbon::parse('1990-01-01 00:00:00')->addSeconds(
            static::UInt4($value, $use_little_endian)
        );
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return Carbon
     */
    public static function NSec($value, $use_little_endian = false) : Carbon
    {
        if ( ! $use_little_endian || self::systemIsLittleEndian()) {
            $value = self::flipEndianness($value);
        }

        $b = unpack('V2', $value);

        return Carbon::parse('1990-01-01 00:00:00')
                     ->addSeconds($b[ 2 ])
                     ->addMicroseconds(round($b[ 1 ] / 10));
    }


    /**
     * @param  string  $value
     * @param  bool    $use_little_endian
     *
     * @return Carbon
     */
    public static function USec($value, $use_little_endian = false) : Carbon
    {
        return Carbon::parse('1990-01-01 00:00:00')
                     ->addMicroseconds(
                         static::UInt6($value, $use_little_endian) * 10
                     );
    }


    /**
     * @param  string  $value
     *
     * @return mixed
     */
    public static function flipEndianness($value)
    {
        return strrev($value);
    }


    /**
     * @return bool
     */
    public static function systemIsLittleEndian() : bool
    {
        return unpack('S', "\x01\x00")[ 1 ] === 1;
    }

}
