<?php namespace Tests;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Records\Record;
use Hyyppa\Toxx\Records\Records;
use Hyyppa\Toxx\Toxx;

abstract class BaseDatafileTest extends BaseTest
{

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var DataFileInterface
     */
    protected $class;

    /**
     * @var array|null
     */
    protected $fields;


    /**
     *
     */
    public function __construct()
    {
        $this->validateProperties();

        parent::__construct();
    }


    /**
     *
     */
    protected function validateProperties() : void
    {
        if ( ! $this->filename) {
            throw new \LogicException(get_class($this).' must define the `$filename` property');
        }

        if ( ! $this->class) {
            throw new \LogicException(get_class($this).' must define the `$class` property');
        }
    }


    /**
     * @return DataFileInterface
     */
    protected function loadDataFile() : DataFileInterface
    {
        return Toxx::load(
            $this->data($this->filename),
            $this->fields
        );
    }


    /**
     *
     */
    protected function _testBasicRecord() : void
    {
        $dat = $this->loadDataFile();

        $record = $dat->first();
        $this->assertInstanceOf(Record::class, $record);

        $expected = [
            'TIMESTAMP'           => '2020-03-08 19:35:00',
            'panel_temp'          => 26.86,
            'battery_voltage'     => 12.94,
            'battery_voltage_Min' => 12.94,
            'SECONDS'             => 952544100,
        ];

        $this->assertJsonAndArrayLike($expected, $record);
    }


    /**
     *
     */
    protected function _testBasicRecordCollection() : void
    {
        $dat = $this->loadDataFile();

        $records = $dat->first(2);
        $this->assertInstanceOf(Records::class, $records);
        $this->assertInstanceOf(Record::class, $records[ 0 ]);
        $this->assertInstanceOf(Record::class, $records[ 1 ]);

        $expected = [
            [
                'TIMESTAMP'           => '2020-03-08 19:35:00',
                'panel_temp'          => 26.86,
                'battery_voltage'     => 12.94,
                'battery_voltage_Min' => 12.94,
                'SECONDS'             => 952544100,
            ], [
                'TIMESTAMP'           => '2020-03-08 19:40:00',
                'panel_temp'          => 26.86,
                'battery_voltage'     => 12.94,
                'battery_voltage_Min' => 12.94,
                'SECONDS'             => 952544400,
            ],
        ];

        $this->assertJsonAndArrayLike($expected, $records);
        $this->assertJsonAndArrayLike($expected[ 0 ], $records[ 0 ]);
        $this->assertJsonAndArrayLike($expected[ 1 ], $records[ 1 ]);
    }


    /**
     *
     */
    protected function _testHumanOutput() : void
    {
        $dat = $this->loadDataFile();

        $records = $dat->first(2);

        $expected = [
            952544100 => [
                'TIMESTAMP'           => '2020-03-08 19:35:00',
                'panel_temp'          => '26.86°C',
                'battery_voltage'     => '12.94volts',
                'battery_voltage_Min' => '12.94volts',
                'SECONDS'             => 952544100,
            ],
            952544400 => [
                'TIMESTAMP'           => '2020-03-08 19:40:00',
                'panel_temp'          => '26.86°C',
                'battery_voltage'     => '12.94volts',
                'battery_voltage_Min' => '12.94volts',
                'SECONDS'             => 952544400,
            ],
        ];

        $this->assertArrayHas($expected[ 952544100 ], $records[ 0 ]->human());
        $this->assertArrayHas($expected[ 952544400 ], $records[ 1 ]->human());
        $this->assertArrayHas($expected, $records->human());
    }


    /**
     *
     */
    protected function _testPaging() : void
    {
        $dat = $this->loadDataFile();

        $expected = [
            [
                [
                    'TIMESTAMP'           => '2020-03-08 19:35:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952544100,
                ], [
                    'TIMESTAMP'           => '2020-03-08 19:40:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952544400,
                ],
            ], [
                [
                    'TIMESTAMP'           => '2020-03-08 19:45:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952544700,
                ], [
                    'TIMESTAMP'           => '2020-03-08 19:50:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952545000,
                ],
            ], [
                [
                    'TIMESTAMP'           => '2020-03-08 19:55:00',
                    'panel_temp'          => 26.85,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952545300,
                ], [
                    'TIMESTAMP'           => '2020-03-08 20:00:00',
                    'panel_temp'          => 26.85,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952545600,
                ],
            ],
        ];

        $records = $dat->page(1, 2);
        $this->assertJsonAndArrayLike($expected[ 0 ], $records);

        $records = $dat->page(2, 2);
        $this->assertJsonAndArrayLike($expected[ 1 ], $records);

        $records = $dat->page(3, 2);
        $this->assertJsonAndArrayLike($expected[ 2 ], $records);

    }


    /**
     *
     */
    protected function _testScanning() : void
    {
        $dat = $this->loadDataFile();

        $expected = [
            [
                [
                    'TIMESTAMP'           => '2020-03-08 19:35:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952544100,
                ], [
                    'TIMESTAMP'           => '2020-03-08 19:40:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952544400,
                ],
            ], [
                [
                    'TIMESTAMP'           => '2020-03-08 19:45:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952544700,
                ], [
                    'TIMESTAMP'           => '2020-03-08 19:50:00',
                    'panel_temp'          => 26.86,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952545000,
                ],
            ], [
                [
                    'TIMESTAMP'           => '2020-03-08 19:55:00',
                    'panel_temp'          => 26.85,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952545300,
                ], [
                    'TIMESTAMP'           => '2020-03-08 20:00:00',
                    'panel_temp'          => 26.85,
                    'battery_voltage'     => 12.94,
                    'battery_voltage_Min' => 12.94,
                    'SECONDS'             => 952545600,
                ],
            ], [
                [
                    'TIMESTAMP'           => '2020-03-13 17:45:00',
                    'panel_temp'          => 27.08,
                    'battery_voltage'     => 12.93,
                    'battery_voltage_Min' => 12.93,
                    'SECONDS'             => 952969500,
                ], [
                    'TIMESTAMP'           => '2020-03-13 17:50:00',
                    'panel_temp'          => 27.09,
                    'battery_voltage'     => 12.93,
                    'battery_voltage_Min' => 12.93,
                    'SECONDS'             => 952969800,
                ],
            ], [
                [
                    'TIMESTAMP'           => '2020-03-13 17:55:00',
                    'panel_temp'          => 27.09,
                    'battery_voltage'     => 12.93,
                    'battery_voltage_Min' => 12.93,
                    'SECONDS'             => 952970100,
                ], [
                    'TIMESTAMP'           => '2020-03-13 18:00:00',
                    'panel_temp'          => 27.09,
                    'battery_voltage'     => 12.93,
                    'battery_voltage_Min' => 12.93,
                    'SECONDS'             => 952970400,
                ],
            ],
        ];

        $records = $dat->first(2);
        $this->assertJsonAndArrayLike($expected[ 0 ], $records);

        $records = $dat->next(2);
        $this->assertJsonAndArrayLike($expected[ 1 ], $records);

        $records = $dat->next(2);
        $this->assertJsonAndArrayLike($expected[ 2 ], $records);

        $records = $dat->prev(2);
        $this->assertJsonAndArrayLike($expected[ 1 ], $records);

        $records = $dat->prev(2);
        $this->assertJsonAndArrayLike($expected[ 0 ], $records);

        $records = $dat->prev(2);
        $this->assertJsonAndArrayLike($expected[ 0 ], $records);

        $records = $dat->last(2);
        $this->assertJsonAndArrayLike($expected[ 4 ], $records);

        $records = $dat->next(2);
        $this->assertJsonAndArrayLike($expected[ 4 ], $records);

        $records = $dat->prev(2);
        $this->assertJsonAndArrayLike($expected[ 3 ], $records);

        $records = $dat->next(2);
        $this->assertJsonAndArrayLike($expected[ 4 ], $records);

        $records = $dat->next(2);
        $this->assertJsonAndArrayLike($expected[ 4 ], $records);
    }


    /**
     *
     */
    protected function _testDateRange() : void
    {
        $dat = $this->loadDataFile();

        $records = $dat->dateRange('2020-03-09 01:00:00', '2020-03-10 01:00:00');

        $this->assertEquals(289, $records->count());

        $first = [
            'TIMESTAMP'           => '2020-03-09 01:00:00',
            'panel_temp'          => 26.03,
            'battery_voltage'     => 12.94,
            'battery_voltage_Min' => 12.94,
            'SECONDS'             => 952563600,
        ];

        $last = [
            'TIMESTAMP'           => '2020-03-10 01:00:00',
            'panel_temp'          => 25.26,
            'battery_voltage'     => 12.95,
            'battery_voltage_Min' => 12.95,
            'SECONDS'             => 952650000,
        ];

        $this->assertJsonAndArrayLike($first, $records[ 0 ]);
        $this->assertJsonAndArrayLike($last, $records[ 288 ]);
    }

}
