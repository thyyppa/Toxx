<?php namespace Tests;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Format\Tob1\Tob1;

class Tob1Test extends BaseDatafileTest
{

    /**
     * @var string
     */
    protected $filename = 'DemoOutputTob1.dat';

    /**
     * @var DataFileInterface
     */
    protected $class = Tob1::class;


    /**
     *
     */
    public function testFileLoad() : void
    {
        $dat = $this->loadDataFile();

        $this->assertInstanceOf($this->class, $dat);
    }


    /**
     *
     */
    public function testFileHeader() : void
    {
        $dat = $this->loadDataFile();

        $this->assertEquals([
            'format'        => 'TOB1',
            'station'       => '__STATION_NAME__',
            'datalogger'    => '__DATALOGGER_MODEL__',
            'serial_number' => '__SERIAL_NUMBER__',
            'os_version'    => '__OS_VERSION__',
            'dld_name'      => '__DLD_NAME__',
            'dld_signature' => '__DLD_SIGNATURE__',
            'table'         => '__TABLE_NAME_TOB1__',
        ], $dat->info());

        $this->assertEquals([
            0 => 'SECONDS',
            1 => 'NANOSECONDS',
            2 => 'RECORD',
            3 => 'panel_temp',
            4 => 'battery_voltage',
            5 => 'battery_voltage_Min',
        ], $dat->fields());

        $this->assertEquals([
            0 => 'SECONDS',
            1 => 'NANOSECONDS',
            2 => 'RN',
            3 => '°C',
            4 => 'volts',
            5 => 'volts',
        ], $dat->units());

        $this->assertEquals([
            0 => '',
            1 => '',
            2 => '',
            3 => 'Smp',
            4 => 'Smp',
            5 => 'Min',
        ], $dat->processing());

    }


    /**
     *
     */
    public function testBasicRecord() : void
    {
        $this->_testBasicRecord();
    }


    /**
     *
     */
    public function testBasicRecordCollection() : void
    {
        $this->_testBasicRecordCollection();
    }


    /**
     *
     */
    public function testHumanUnits() : void
    {
        $this->_testHumanOutput();
    }


    /**
     *
     */
    public function testPaging() : void
    {
        $this->_testPaging();
    }


    /**
     *
     */
    public function testScanning() : void
    {
        $this->_testScanning();
    }


    /**
     *
     */
    public function testDateRange() : void
    {
        $this->_testDateRange();
    }

}
