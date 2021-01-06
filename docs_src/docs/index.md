path: tree/master/src

# Toxx Reader

Cum historia messis, omnes omniaes reperire peritus, nobilis vigiles. Ecce. Cur valebat experimentum?
Secundus armarium mechanice resuscitabos fiscina est.
Sunt fluctuies carpseris brevis, dexter castores.
Primus solem una tractares ausus est.

## Requirements

!!! info ""
    * [x] PHP 7.2+
    * [x] ext-xmlreader

## Installation

### Using Composer

Install using the composer command

!!! info ""
    ```
    composer require thyyppa/toxx
    ```

or by adding

```json
{
    "require": {
        "thyyppa/toxx": "dev-master"
    }
}
```

to your `composer.json` file and running ```composer install```

## Setting up LoggerNet

For best results the output files should be configured as described below. Most of the options
are the default. Open up LoggerNet and you should see the big blue main screen. Navigate to `Main->Setup`
and you should see the Setup Screen.

![](/assets/img/main.png)

### LoggerNet Configuration

!!! danger "Make sure you're not in EZ View"
    **If you are in `EZ (Simplified) View` you will need to click `Std View` on the top right of the window to proceed.**

On the Setup Screen, select your datalogger on the left under `Entire Network`,
then, just to the right of that choose the table you would like to collect.

After you've chosen your table click the `Data Files` tab.

Set your output file name and be sure that `Included For Scheduled Collection` is checked.

![](/assets/img/setup_screen.png)

### Choose your output file type

---

???+ success "TOA5 - Recommended Format"
    #### TOA5

    TOA5 is a comma separated format with a 4-line header containing metadata about the datalogger and the readings.
    
    ![](/assets/img/example_toa5.png)

    !!! alert "Output File Options"
        * [x] Include Timestamp
        * [x] Include Record Number
        * [ ] Midnight is 2400


??? success "TOB1 - Recommended Format"
    #### TOB1

    TOB1 is a binary format with a CSV-style header.
    
    If you will only be reading this file with LoggerNet and Toxx, and have no need to
    read the file in plaintext, this is by far the fastest and most efficient format.
    
    ![](/assets/img/example_tob1.png)
    
    !!! alert "Output File Options"
        * [x] Include Timestamp
        * [x] Include Record Number


??? info "TOACI1"
    #### TOACI1

    TOACI1 is a comma separated format with a 2-line header containing metadata that defines the station name, table name, and field names.
    
    There are no output file options that need to be set.
    
    ![](/assets/img/example_toaci1.png)


??? info "CSV"
    #### CSV

    The CSV format only contains record data, it does not define field names.
    
    When properly configured for use with Toxx the first values will be: `table id`, `year`, `day of year`, `4-digit 24hr time`, and `record number`.
    These fields should not be included when passing field names to the `#!ts Toxx::load()` method.

    !!! danger "Important"
        When passing field names, **do not include table id, year, day, time, or the record number**.
    
        They are automatically prepended. You should **only pass the field names defined in your DataTable**.
    
    ![](/assets/img/example_csv.png)
    
    !!! alert "Output File Options"
        * [x] Year
        * [x] Day
        * [x] Hour/Minutes
        * [x] Seconds
        * [ ] Midnight is 2400
        * [x] Include Array ID (value doesn't matter)
        * [x] Array Datalogger Format - set to **[ Hour/Minutes and Seconds ]**


??? warning "ASCII - This format is sketchyzz"
    The format defined in the LoggerNet documentation does not match what is output by LoggerNet.

    This section documents what is output by LoggerNet, but due to the discrepency **this format is not recommended**.
    
    ---  
    #### ASCII
    
    The ASCII format only contains record data, it does not define field names.
    
    When properly configured for use with Toxx the first values will be: `timestamp string` and `record number`.
    These fields should not be included when passing field names to the `#!ts Toxx::load()` method.
    
    !!! danger "Important"
        When passing field names, **do not include the timestamp or the record number**.
    
        They are automatically prepended. You should **only pass the field names defined in your DataTable**.
        
        See [Usage](#usage) below for example.
    
    ![](/assets/img/example_ascii.png)
    
    !!! alert "Output File Options"
        * [x] Include Timestamp
        * [x] Include Record Number
        * [x] Quoted Strings
        * [ ] Midnight is 2400


??? danger "CSIXML - This format is not recommended"
    Due to how XML is parsed in PHP, this format can be **extremely slow** and cause **high memory usage** when used with large datasets.

    **CsiXml can be used with Toxx, but it is not recommended.**
    
    ---
    #### CSIXML

    ![](/assets/img/example_xml.png)
    
    !!! alert "Output File Options"
        * [x] Include Timestamp
        * [x] Include Record Number
        * [ ] Midnight is 2400

---


## Basic Usage

```php linenums="1"
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load("filename.dat");

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



```php linenums="1"
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load("filename.dat");

$json = $dat->first()->json();

```

!!! example "$json ="
    ```json
    {
        "timestamp": "2020-03-08 19:35:00",
        "panel_temp": 26.86,
        "battery_voltage": 12.94,
        "battery_voltage_min": 12.94
    }
    ```
