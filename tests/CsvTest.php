<?php namespace Tests;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Format\CSV\CSV;

class CsvTest extends BaseDatafileTest
{

    /**
     * @var string
     */
    protected $filename = 'DemoOutputCSV.dat';

    /**
     * @var DataFileInterface
     */
    protected $class = CSV::class;

    /**
     * @var array
     */
    protected $fields = [
        2 => 'panel_temp',
        3 => 'battery_voltage',
        4 => 'battery_voltage_Min',
    ];


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
            0 => 'TABLE',
            1 => '_YEAR_',
            2 => '_DAY_',
            3 => '_TIME_',
            4 => '_SECONDS_',
            5 => 'panel_temp',
            6 => 'battery_voltage',
            7 => 'battery_voltage_Min',
        ], $dat->fields());
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
