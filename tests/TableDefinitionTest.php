<?php namespace Tests;

use Hyyppa\Toxx\Exceptions\FileException;
use Hyyppa\Toxx\Exceptions\TdfException;
use Hyyppa\Toxx\TableDefinition\TDF;

class TableDefinitionTest extends BaseTest
{


    /**
     *
     */
    public function testTables() : void
    {
        $tdf = new TDF($this->data('table_definition.tdf'));

        $this->assertArrayHas([
            'TestToa5' => [
                'fields'   => 26,
                'interval' => 10,
            ],
        ], $tdf->all()->toArray());

        $this->assertArrayHas([
            'DemoOutputCSV' => [
                'fields'   => 3,
                'interval' => 300,
            ],
        ], $tdf->all()->toArray());

        $this->assertArrayHas([
            'panel_temp',
            'battery_voltage',
            'battery_voltage_Min',
        ], $tdf->get('DemoOutputCSV')->fieldNames());

        $this->assertArrayHas([
            'DemoOutputCSV' => [
                [
                    'name'       => 'panel_temp',
                    'type'       => 'FP2',
                    'processing' => 'Smp',
                    'unit'       => '°C',
                ], [
                    'name'       => 'battery_voltage',
                    'type'       => 'FP2',
                    'processing' => 'Smp',
                    'unit'       => 'volts',
                ], [
                    'name'       => 'battery_voltage_Min',
                    'type'       => 'FP2',
                    'processing' => 'Min',
                    'unit'       => 'volts',
                ],
            ],
        ], $tdf->all()->toArrayWithFields());

        // lazy table access
        $this->assertArrayHas([
            'panel_temp',
            'battery_voltage',
            'battery_voltage_Min',
        ], $tdf->DemoOutputCSV->fieldNames());

    }


    /**
     *
     */
    public function testTdfNotExistException() : void
    {
        $this->expectException(FileException::class);
        new TDF('does_not_exist.tdf');
    }


    /**
     *
     */
    public function testTdfEmptyException() : void
    {
        $this->expectException(FileException::class);
        new TDF($this->data('empty.txt'));
    }


    /**
     *
     */
    public function testTdfReadOnly() : void
    {
        $this->expectException(TdfException::class);
        $tdf = new TDF($this->data('table_definition.tdf'));

        $tdf->DemoOutputCSV = 'error';
    }


    public function testField() : void
    {
        $tdf   = new TDF($this->data('table_definition.tdf'));
        $table = $tdf->get('DemoOutputCSV');

        $this->assertEquals(188, $table->size);
        $this->assertEquals(300, $table->interval);

        $fields = $table->fields;
        $this->assertCount(3, $fields);

        $field = $fields->first();

        $this->assertEquals([
            'name'       => 'panel_temp',
            'type'       => 'FP2',
            'processing' => 'Smp',
            'unit'       => '°C',
        ], $field->toArray());

        $this->assertEquals('panel_temp', $field->name);
        $this->assertEquals('FP2', $field->type);
        $this->assertEquals('Smp', $field->processing);
        $this->assertEquals('°C', $field->unit);
    }

}
