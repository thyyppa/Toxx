# TOACI1
---

TOACI1 is a comma separated format[^1] with a 2-line header containing metadata that defines the station name, table name, and field names.


## LoggerNet Configuration
--8<-- "config/toaci1.md"


## Structure

<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center">TOACI1 Format</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">"TOACI1"</td>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">Station Name</td>
            <td style="text-align: center">Table Name</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center">Field Names</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center">Record</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center">Record</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center">...</td>
        </tr>
    </tbody>
</table>


## Example File

```csv
"TOACI1","Ridge Station","Ridge_Table"
"TMSTAMP","RECNBR","panel_temp","battery_voltage","battery_voltage_Min"
"2020-03-08 19:35:00",0,26.86,12.94,12.94
"2020-03-08 19:40:00",0,26.86,12.94,12.94
```


## Usage

```php
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load('toaci1_file.dat');

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

--8<-- "rfc4180.md"
