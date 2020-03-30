<?php namespace Tests;

use Hyyppa\Toxx\Format\General\FileHeader;

class FileHeaderTest extends BaseTest
{

    public function testFileHeaderSettersGetters() : void
    {
        $array = ['a', 'b', 'c'];

        $header = new FileHeader();

        $header->setInfo($array);
        $this->assertEquals($array, $header->info);

        $header->setFields($array);
        $this->assertEquals($array, $header->fields);

        $header->setUnits($array);
        $this->assertEquals($array, $header->units);

        $header->setProcessing($array);
        $this->assertEquals($array, $header->processing);

        $header->setTypes($array);
        $this->assertEquals($array, $header->types);

        $header->setSize(3);
        $this->assertEquals(3, $header->size);


        $header = new FileHeader();

        $header->info = $array;
        $this->assertEquals($array, $header->info);

        $header->fields = $array;
        $this->assertEquals($array, $header->fields);

        $header->units = $array;
        $this->assertEquals($array, $header->units);

        $header->processing = $array;
        $this->assertEquals($array, $header->processing);

        $header->types = $array;
        $this->assertEquals($array, $header->types);

        $header->size = 3;
        $this->assertEquals(3, $header->size);
    }


    public function testArrayAccess() : void
    {
        $array = ['a', 'b', 'c'];

        $header = (new FileHeader())
            ->setFields($array)
            ->setUnits($array)
            ->setProcessing($array)
            ->setTypes($array);

        $this->assertEquals('a', $header->field(0));
        $this->assertEquals('b', $header->field(1));
        $this->assertEquals('c', $header->field(2));

        $this->assertEquals('a', $header->unit(0));
        $this->assertEquals('b', $header->unit(1));
        $this->assertEquals('c', $header->unit(2));

        $this->assertEquals('a', $header->process(0));
        $this->assertEquals('b', $header->process(1));
        $this->assertEquals('c', $header->process(2));

        $this->assertEquals('a', $header->type(0));
        $this->assertEquals('b', $header->type(1));
        $this->assertEquals('c', $header->type(2));
    }


}
