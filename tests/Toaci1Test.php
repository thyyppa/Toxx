<?php namespace Tests;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Format\Toaci1\Toaci1;

class Toaci1Test extends BaseDatafileTest
{

    /**
     * @var string
     */
    protected $filename = 'DemoOutputToaci1.dat';

    /**
     * @var DataFileInterface
     */
    protected $class = Toaci1::class;


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
            'format'  => 'TOACI1',
            'station' => '__STATION_NAME__',
            'table'   => '__TABLE_NAME_TOACI1__',
        ], $dat->info());

        $this->assertEquals([
            0 => 'TMSTAMP',
            1 => 'RECNBR',
            2 => 'panel_temp',
            3 => 'battery_voltage',
            4 => 'battery_voltage_Min',
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
