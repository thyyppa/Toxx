<?php namespace Hyyppa\Toxx\TableDefinition\Structure;

class Type
{

    /**
     * Values from LoggerNet SDK Programmer's Manual, Section 16.7.1 "Table of Data Type Enumeration"
     *
     * @see https://s.campbellsci.com/documents/us/manuals/loggernet-and-lnserver-sdk.pdf
     */
    private const TYPES = [
        1  => 'UINT1',            // 1-byte unsigned int (BE)
        2  => 'UINT2',            // 2-byte unsigned int (BE)
        3  => 'UINT4',            // 4-byte unsigned int (BE)
        4  => 'INT1',             // 1-byte signed int (BE)
        5  => 'INT2',             // 2-byte signed int (BE)
        6  => 'INT4',             // 4-byte signed int (BE)
        7  => 'FP2',              // 2-byte final storage float (aka FS2)
        8  => 'FP4',              // 4-byte final storage float (aka FS4)
        9  => 'IEEE4',            // 4-byte IEEE floating point (BE)
        10 => 'BOOL',             // 1-byte boolean
        11 => 'ASCII',            // n-byte fixed length string
        12 => 'SEC',              // 4-byte unsigned int storing seconds since 1990-01-01
        13 => 'USEC',             // 6-byte unsigned int storing 10s of microseconds since 1990-01-01
        14 => 'NSEC',             // 8-byte, 4-bytes for sec since 1990 + 4-bytes for additional nanoseconds (BE)
        15 => 'FP3',              // 3-byte final storage float (aka FS3)
        16 => 'ASCII',            // n+1-byte null terminated variable length string
        17 => 'BOOL8',            // 1-byte bit field
        18 => 'IEEE8',            // 8-byte IEEE floating point (BE)
        19 => 'INT2_LSF',         // 2-byte signed int (LE)
        20 => 'INT4_LSF',         // 4-byte singed int (LE)
        21 => 'INT2_LSF',         // 2-byte singed int (LE)
        22 => 'UINT4_LSF',        // 4-byte unsinged int (LE)
        23 => 'NSEC_LSF',         // 8-byte, same as NSEC but little endian (LE)
        24 => 'IEEE4_LSF',        // 4-byte IEEE floating point (LE)
        25 => 'IEEE8_LSF',        // 8-byte IEEE floating point (LE)
        26 => 'FS4',              // 4-byte floating point (aka FS4)
        27 => 'BOOL2',            // 2-byte boolean, though one source says 4-byte storage for either FP2 or FP3
        28 => 'BOOL4',            // 4-byte boolean, though one source says 8-byte of nanoseconds since 1990 (LE)
        29 => 'LGRDATE',          // 8-byte of nanoseconds since 1990 (BE)
        30 => 'BOOL2',            // 2-byte boolean, non-zero = true
        31 => 'BOOL4',            // 4-byte boolean, non-zero = true
        32 => 'INT8',             // 8-byte signed int (BE)
        33 => 'INT8_LSF',         // 8-byte signed int (LE)
    ];

    /**
     * @var int
     */
    private $_type;

    /**
     * @var bool
     */
    private $_read_only;


    /**
     * The first bit is for "read only", the other 7 are the data type
     *
     * @param  string  $value
     */
    public function __construct(string $value)
    {
        $this->_type      = $value & 0x7f;
        $this->_read_only = $value >> 7 > 0;
    }


    /**
     * @return string
     */
    public function __toString() : string
    {
        return self::TYPES[ $this->_type ] ?? '';
    }


    /**
     * @return bool
     */
    public function isReadOnly() : bool
    {
        return $this->_read_only;
    }


    /**
     * @return string
     */
    public function name() : string
    {
        return self::TYPES[ $this->_type ] ?? '';
    }


    /**
     * @param $id
     *
     * @return string|null
     */
    public static function check($id) : ?string
    {
        return self::TYPES[ $id ] ?? null;
    }

}
