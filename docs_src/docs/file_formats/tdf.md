# TDF

!!! info "This page is for reference only"
    Table Definition Files are created by LoggerNet and not modified by Toxx in any way.

------------------------------------


Table Definition Files are created whenever you compile a program using CRBASIC Editor.

Toxx can use these files to better understand your headerless data formats, adding
meta data to your records so that you can use CSV and ASCII with as much information  
as you would have when using TOA5 and TOB1.


## Structure

!!! warning ""
    This information was gathered by observation and may be incorrect.

The TDF file is a simple binary that begins with a single byte `0x01`, which is presumably the version number, followed by a null terminated list of `table` structures.

```
{
    byte version,           // usually 0x01
    TABLE tables[]          // null terminated
}
```

### Table

```
{
    string field_name,      // null terminated
    uint32 size,            // table size
    FIELD_TYPE time_type,   // data type used for timestamps (usually 0x0E for 8-byte NSEC)
    NSEC start_time,        // time that collection began, usually 0 (1990-01-01)
    NSEC interval,          // table collection interval
    FIELD fields[],         // list of fields (4-byte zero terminated?)
    byte null               // null
}
```

### Field

```
{
    FIELD_TYPE type,        // the first bit indicates read-only, the next 7 indicate field type
    string name,            // null terminated
    string alias,           // null terminated
    string processing,      // null terminated
    string unit,            // null terminated
    string description,     // null terminated
    uint32 start_index,     // dimension start index, usually 1 (?)
    uint32 size,            // total length of all dimensions, typically 1 for simple readings
    uint32 dimension[]      // zero terminated - if size is 1 this is just a single uint32 of 0
}
```

### Field Type

!!! warning ""
    These are best-guesses, and many are unused

    See [LoggerNet SDK Programmer's Manual 4.5 - Section 16.7.1](https://s.campbellsci.com/documents/us/manuals/loggernet-and-lnserver-sdk.pdf#page=117)
    for more info.

```
enum byte {
    UNKNOWN,
    UINT1,                  // 0x01 -- 1-byte unsigned int (BE)
    UINT2,                  // 0x02 -- 2-byte unsigned int (BE)
    UINT4,                  // 0x03 -- 4-byte unsigned int (BE)
    INT1,                   // 0x04 -- 1-byte signed int (BE)
    INT2,                   // 0x05 -- 2-byte signed int (BE)
    INT4,                   // 0x06 -- 4-byte signed int (BE)
    FP2,                    // 0x07 -- 2-byte final storage float (aka FS2)
    FP4,                    // 0x08 -- 4-byte final storage float (aka FS4)
    IEEE4,                  // 0x09 -- 4-byte IEEE floating point (BE)
    BOOL,                   // 0x0A -- 1-byte boolean
    ASCII,                  // 0x0B -- n-byte fixed length string
    SEC,                    // 0x0C -- 4-byte unsigned int storing seconds since 1990-01-01
    USEC,                   // 0x0D -- 6-byte unsigned int storing 10s of microseconds since 1990-01-01
    NSEC,                   // 0x0E -- 8-byte, 4-bytes for sec since 1990 + 4-bytes for additional nanoseconds (BE)
    FP3,                    // 0x0F -- 3-byte final storage float (aka FS3)
    ASCIIZ,                 // 0x10 -- n+1-byte null terminated variable length string
    BOOL8,                  // 0x11 -- 1-byte bit field
    IEEE8,                  // 0x12 -- 8-byte IEEE floating point (BE)
    INT2_LSF,               // 0x13 -- 2-byte signed int (LE)
    INT4_LSF,               // 0x14 -- 4-byte singed int (LE)
    INT2_LSF2,              // 0x15 -- 2-byte singed int (LE) (duplicate?)
    UINT4_LSF,              // 0x16 -- 4-byte unsinged int (LE)
    NSEC_LSF,               // 0x17 -- 8-byte, same as NSEC but little endian (LE)
    IEEE4_LSF,              // 0x18 -- 4-byte IEEE floating point (LE)
    IEEE8_LSF,              // 0x19 -- 8-byte IEEE floating point (LE)
    FS4,                    // 0x1A -- 4-byte floating point (aka FS4)
    BOOL2,                  // 0x1B -- 2-byte boolean, though one source says 4-byte storage for either FP2 or FP3
    BOOL4,                  // 0x1C -- 4-byte boolean, though one source says 8-byte of nanoseconds since 1990 (LE)
    LGRDATE,                // 0x1D -- 8-byte of nanoseconds since 1990 (BE)
    BOOL2_LSF,              // 0x1E -- 2-byte boolean, non-zero = true
    BOOL4_LSF,              // 0x1F -- 4-byte boolean, non-zero = true
    INT8,                   // 0x20 -- 8-byte signed int (BE)
    INT8_LSF                // 0x21 -- 8-byte signed int (LE)
}
```

## References

!!! info ""
    - [LoggerNet SDK Programmer's Manual 4.5](https://s.campbellsci.com/documents/us/manuals/loggernet-and-lnserver-sdk.pdf)
    - [Loggernet Product Manual 4.6](https://s.campbellsci.com/documents/us/manuals/loggernet.pdf)
    - [https://github.com/kitplummer/PyPak](https://github.com/kitplummer/PyPak)
    - [https://github.com/sutanay/PbCdlComm](https://github.com/sutanay/PbCdlComm)

