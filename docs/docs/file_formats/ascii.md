# Ascii

!!! warning "This format is not recommended"
    The format defined in the LoggerNet documentation does not match what is output by LoggerNet.

    This page documents what is output by LoggerNet, but due to the discrepency **this format is not recommended**.

---

The ASCII format only contains record data, it does not define field names.

When properly configured for use with Toxx the first values will be: `timestamp string` and `record number`.
These fields should not be included when passing field names to the `#!ts Toxx::load()` method.


!!! danger "Important"
    When passing field names, **do not include the timestamp or the record number**.

    They are automatically prepended. You should **only pass the field names defined in your DataTable**.
    
    See [Usage](#usage) below for example.

## LoggerNet Configuration
--8<-- "config/ascii.md"


## Example File

```csv
"2020-03-08 19:35:00",0,26.86,12.94,12.94
"2020-03-08 19:40:00",0,26.86,12.94,12.94
"2020-03-08 19:45:00",0,26.86,12.94,12.94
"2020-03-08 19:50:00",1,26.86,12.94,12.94
"2020-03-08 19:55:00",2,26.85,12.94,12.94
```

## Usage

```php
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load('ascii_file.dat',[
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
