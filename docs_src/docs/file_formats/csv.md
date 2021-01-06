# CSV

!!! warning "Please read carefully"
    This is a commonly used format, but it is important that the field names are provided properly.

---

The CSV format only contains record data, it does not define field names.

When properly configured for use with Toxx the first values will be: `table id`, `year`, `day of year`, `4-digit 24hr time`, and `record number`.
These fields should not be included when passing field names to the `#!ts Toxx::load()` method.


!!! danger "Important"
    When passing field names, **do not include table id, year, day, time, or the record number**.

    They are automatically prepended. You should **only pass the field names defined in your DataTable**.
    
    See [Usage](#usage) below for example.

## LoggerNet Configuration
--8<-- "config/csv.md"


## Example File

```csv
101,2020,68,1935,0,26.86,12.94,12.94
101,2020,68,1940,0,26.86,12.94,12.94
101,2020,68,1945,0,26.86,12.94,12.94
101,2020,68,1950,0,26.86,12.94,12.94
101,2020,68,1955,0,26.85,12.94,12.94
```

## Usage

```php
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load('csv_file.dat',[
    'panel_temp',
    'battery_voltage',
    'battery_voltage_Min'
]);

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

---
