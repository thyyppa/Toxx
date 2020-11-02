<?php namespace Tests;

use Hyyppa\Toxx\Format\Toa5\Toa5;
use Hyyppa\Toxx\Toxx;

class TestToa5 extends BaseDatafileTest
{


    public function testFileLoad() : void
    {
        $dat = Toxx::load(
            $this->data('DemoOutputToa5.dat')
        );

        $this->assertInstanceOf(Toa5::class, $dat);
    }


    public function testFileHeader() : void
    {
        $dat = Toxx::load(
            $this->data('DemoOutputToa5.dat')
        );

        $this->assertEquals([
            'format'        => 'TOA5',
            'station'       => '__STATION_NAME__',
            'datalogger'    => '__DATALOGGER_MODEL__',
            'serial_number' => '__SERIAL_NUMBER__',
            'os_version'    => '__OS_VERSION__',
            'dld_name'      => '__DLD_NAME__',
            'dld_signature' => '__DLD_SIGNATURE__',
            'table'         => '__TABLE_NAME_TOA5__',
        ], $dat->info());

        $this->assertEquals([
            0 => 'TIMESTAMP',
            1 => 'RECORD',
            2 => 'panel_temp',
            3 => 'battery_voltage',
            4 => 'battery_voltage_Min',
        ], $dat->fields());

        $this->assertEquals([
            0 => 'TS',
            1 => 'RN',
            2 => '°C',
            3 => 'volts',
            4 => 'volts',
        ], $dat->units());

        $this->assertEquals([
            0 => '',
            1 => '',
            2 => 'Smp',
            3 => 'Smp',
            4 => 'Min',
        ], $dat->processing());
    }


    public function testBasicRecord() : void
    {
        $this->_testBasicRecord('DemoOutputToa5.dat');
    }


    public function testBasicRecordCollection() : void
    {
        $this->_testBasicRecordCollection('DemoOutputToa5.dat');
    }

}
