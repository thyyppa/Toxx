<?php namespace Hyyppa\Toxx\Records;

use Hyyppa\Toxx\Contracts\Reading\ReadingCollectionInterface;
use Hyyppa\Toxx\Format\General\Collection;

class Readings extends Collection implements ReadingCollectionInterface
{


    public function addReading($value, $index) : ReadingCollectionInterface
    {
        return $this->push(
            new Reading(
                $value,
                $index,
                $this->_header
            )
        );
    }

}
