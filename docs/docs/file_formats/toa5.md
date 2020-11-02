# TOA5

!!! success "Recommended Format"

---

TOA5 is a comma separated format[^1] with a 4-line header containing metadata about the datalogger and the readings.

## LoggerNet Configuration
--8<-- "config/toa5.md"


## Structure

<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center">TOA5 Format</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center;border-right:1px solid rgba(0,0,0,.2);">"TOA5"</td>
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
            <td colspan="8" style="text-align: center">Record</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">Record</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">...</td>
        </tr>
    </tbody>
</table>


## Example File

```csv
"TOA5","Ridge Station","CR1000X","12345","CR1000X.Std.03.02","CPU:ridge_station.CR1X","12345","Ridge_Table"
"TIMESTAMP","RECORD","panel_temp","battery_voltage","battery_voltage_Min"
"TS","RN","°C","volts","volts"
"","","Smp","Smp","Min"
"2020-03-08 19:35:00",0,26.86,12.94,12.94
"2020-03-08 19:40:00",0,26.86,12.94,12.94
"2020-03-08 19:45:00",0,26.86,12.94,12.94
...
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

--8<-- "rfc4180.md"
--8<-- "processing.md"
