<?php namespace Hyyppa\Toxx\Contracts\TDF;

/**
 * @property-read string $name
 * @property-read string $alias
 * @property-read string $processing
 * @property-read string $unit
 * @property-read string $description
 * @property-read string $type
 * @property-read int    $start_index
 * @property-read int    $dimension_size
 * @property-read int    $dimensions
 */
interface FieldInterface
{

    /**
     * @return string
     */
    public function name() : string;


    /**
     * @param  string  $prop
     *
     * @return mixed|null
     */
    public function get(string $prop);


    /**
     * @return array
     */
    public function toArray() : array;

}
