# TOB1

!!! success "Recommended Format"

---

TOB1 is a binary format with a CSV-style header.[^1]

If you will only be reading this file with LoggerNet and Toxx, and have no need to
read the file in plaintext, this is by far the fastest and most efficient format.


## LoggerNet Configuration
--8<-- "config/tob1.md"


## Structure

<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center">TOB1 Format</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">"TOB1"</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">Station Name</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">Datalogger Model</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">Serial Number</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">OS Version</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">DLD Name</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">DLD Sig</td>
            <td style="text-align: center">Table Name</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">Field Names</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">Units</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">
                Processing Method
                <sup id="fnref:2"><a class="footnote-ref" href="#fn:2">2</a></sup>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">
                Field Type
                <sup id="fnref:3"><a class="footnote-ref" href="#binary-field-formats">3</a></sup>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">[Binary Record Data]</td>
        </tr>
    </tbody>
</table>


### Binary Field Formats

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Size</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr><td style="font-family:monospace">UINT1                     </td><td>1-byte</td><td>unsigned int</td></tr>
        <tr><td style="font-family:monospace">UINT2                     </td><td>2-byte</td><td>unsigned int</td></tr>
        <tr><td style="font-family:monospace">UINT4                     </td><td>4-byte</td><td>unsigned int</td></tr>
        <tr><td style="font-family:monospace">INT1                      </td><td>1-byte</td><td>signed int</td></tr>
        <tr><td style="font-family:monospace">INT2                      </td><td>2-byte</td><td>signed int</td></tr>
        <tr><td style="font-family:monospace">INT4                      </td><td>4-byte</td><td>signed int</td></tr>
        <tr><td style="font-family:monospace">INT8                      </td><td>8-byte</td><td>signed int</td></tr>
        <tr><td style="font-family:monospace"><a href="#fp2">FP2</a>    </td><td>2-byte</td><td><a href="#fp2">non-standard</a> floating point</td></tr>
        <tr><td style="font-family:monospace"><a href="#fp4">FP4</a>    </td><td>4-byte</td><td><a href="#fp4">non-standard</a> floating point</td></tr>
        <tr><td style="font-family:monospace">IEEE4                     </td><td>4-byte</td><td>IEEE floating point</td></tr>
        <tr><td style="font-family:monospace">IEEE8                     </td><td>8-byte</td><td>IEEE floating point</td></tr>
        <tr><td style="font-family:monospace">ASCII(n)                  </td><td>n-byte</td><td>fixed length null-terminated string</td></tr>
        <tr><td style="font-family:monospace">SEC                       </td><td>4-byte</td><td>unsigned int as seconds since 1990-01-01</td></tr>
        <tr><td style="font-family:monospace">USEC                      </td><td>6-byte</td><td>unsigned int as 10s of microseconds since 1990-01-01</td></tr>
        <tr><td style="font-family:monospace">NSEC                      </td><td>8-byte</td><td>4-bytes for sec since 1990 + 4-bytes nanoseconds</td></tr>
        <tr><td style="font-family:monospace">BOOL                      </td><td>1-byte</td><td>boolean</td></tr>
        <tr><td style="font-family:monospace">BOOL2                     </td><td>2-byte</td><td>boolean</td></tr>
        <tr><td style="font-family:monospace">BOOL4                     </td><td>4-byte</td><td>boolean</td></tr>
        <tr><td style="font-family:monospace">BOOL8                     </td><td>1-byte</td><td>bit field</td></tr>
    </tbody>
</table>

#### FP2

FP2 is widely used as a storage format in Campbell Scientific dataloggers, it is a non-standard two-byte floating point number with a single sign bit, a two-bit negative decimal exponent,
and a 13-bit mantissa.

##### Decoding FP2

```bash

sign     = ( 0x8000 & FP2 ) >> 15
exponent = ( 0x6000 & FP2 ) >> 13
mantissa = ( 0x1FFF & FP2 )

value = mantissa * pow( 10, -exponent ) * ( sign == 0 ? 1 : -1 )

```

#### FP4

!!! warning "Untested"

FP4 is a storage format used in Campbell Scientific dataloggers. It is a non-standard four-byte floating point number with a single sign bit,  
a seven-bit base-two exponent, and a 24-bit mantissa.

##### Decoding FP4

```bash
sign     = ( 0x80000000 & FP4 ) >> 31;
exponent = ( 0x7F000000 & FP4 ) >> 24;
mantissa = ( 0x00FFFFFF & FP4 );

value = mantissa * pow( 2, exponent ) * ( sign == 0 ? 1 : -1 );
```

## Example File

```csv
"TOB1","Ridge Station","CR1000X","12345","CR1000X.Std.03.02","CPU:ridge_station.CR1X","12345","Ridge_Table"
"SECONDS","NANOSECONDS","RECORD","panel_temp","battery_voltage","battery_voltage_Min"
"SECONDS","NANOSECONDS","RN","°C","volts","volts"
"","","","Smp","Smp","Min"
"ULONG","ULONG","ULONG","FP2","FP2","FP2"
[binary record data...]
```


## Usage

```php
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load('toa5_file.dat');

$json = $dat->first()->json();

```

!!! example "$json ="
    ```json
    {
        "TIMESTAMP": "2020-03-08 19:35:00",
        "RECORD": 0,
        "panel_temp": 26.86,
        "battery_voltage": 12.94,
        "battery_voltage_Min": 12.94,
        "SECONDS": 952544100
    }
    ```

## References

!!! info ""
    - [LoggerNet Product Manual 4.6 - Appendix B.1.4](https://s.campbellsci.com/documents/us/manuals/loggernet.pdf#page=479)

--8<-- "rfc4180.md"
--8<-- "processing.md"
[^3]: See [Binary Field Formats Table](#binary-field-formats)
