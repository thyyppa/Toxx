<?php namespace Tests;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Format\CsiXml\CsiXml;

class CsiXmlTest extends BaseDatafileTest
{

    /**
     * @var string
     */
    protected $filename = 'DemoOutputXML.xml';

    /**
     * @var DataFileInterface
     */
    protected $class = CsiXml::class;


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
            'format'        => 'CSIXML',
            'station'       => '__STATION_NAME__',
            'datalogger'    => '__DATALOGGER_MODEL__',
            'serial_number' => '12345',
            'os_version'    => '__OS_VERSION__',
            'dld_name'      => '__DLD_NAME__',
            'dld_signature' => '54321',
            'table'         => '__TABLE_NAME_CSIXML__',
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
            0 => 'Smp',
            1 => 'Smp',
            2 => 'Smp',
            3 => 'Smp',
            4 => 'Min',
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
