# CsiXml

!!! warning "This format is not recommended"
    Due to how XML is parsed in PHP, this format can be **extremely slow** and cause **high memory usage** when used with large datasets.

    **CsiXml can be used with Toxx, but it is not recommended.**

---

## LoggerNet Configuration
--8<-- "config/csixml.md"


## Example File

```xml
<?xml version="1.0"?>
<csixml version="1.0">
  <head>
    <environment>
      <station-name>Ridge Station</station-name>
      <table-name>Ridge_Table</table-name>
      <model>CR1000X</model>
      <serial-no>12345</serial-no>
      <os-version>CR1000X.Std.03.02</os-version>
      <dld-name>CPU:ridge_station.CR1X</dld-name>
      <dld-sig>54321</dld-sig>
    </environment>
    <fields>
      <field name="panel_temp" process="Smp" type="xsd:float" units="&#176;C" />
      <field name="battery_voltage" process="Smp" type="xsd:float" units="volts" />
      <field name="battery_voltage_Min" process="Min" type="xsd:float" units="volts" />
    </fields>
  </head >
  <data>
    <r no="0" time="2020-03-08 19:45:00"><v1>26.86</v1><v2>12.94</v2><v3>12.94</v3></r>
    <r no="1" time="2020-03-08 19:50:00"><v1>26.86</v1><v2>12.94</v2><v3>12.94</v3></r>
    <r no="2" time="2020-03-08 19:55:00"><v1>26.85</v1><v2>12.94</v2><v3>12.94</v3></r>
    <r no="3" time="2020-03-08 20:00:00"><v1>26.85</v1><v2>12.94</v2><v3>12.94</v3></r>
    <r no="4" time="2020-03-08 20:05:00"><v1>26.85</v1><v2>12.94</v2><v3>12.93</v3></r>
  </data>
</csixml>
```

## Usage

```php
<?php

use Hyyppa\Toxx\Toxx;

$dat = Toxx::load('csixml_file.xml');

$json = $dat->first()->json();

```

!!! example "$json ="
    ```json
    {
        "TIMESTAMP": "2020-03-08 19:45:00",
        "RECORD": 0,
        "panel_temp": 26.86,
        "battery_voltage": 12.94,
        "battery_voltage_Min": 12.94,
        "SECONDS": 952544100
    }
    ```

---
