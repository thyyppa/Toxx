<?php namespace Hyyppa\Toxx\Contracts\Reading;

interface ReadingCollectionInterface
{

    public function addReading($value, $index) : ReadingCollectionInterface;

}
