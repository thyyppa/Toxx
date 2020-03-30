<?php namespace Hyyppa\Toxx\Contracts\TDF;


use Carbon\Carbon;
use Hyyppa\Toxx\TableDefinition\Structure\Field;
use Hyyppa\Toxx\TableDefinition\Structure\Fields;

/**
 * @property-read string $name
 * @property-read int    $size
 * @property-read Carbon $time
 * @property-read Carbon $interval
 * @property-read Fields $fields
 */
interface TableInterface
{

    /**
     * @return Fields
     */
    public function fields() : Fields;


    /**
     * @param  string  $name
     *
     * @return Field|null
     */
    public function field(string $name) : ?FieldInterface;


    /**
     * @param  string  $name
     *
     * @return bool
     */
    public function has(string $name) : bool;


    /**
     * @return array
     */
    public function fieldNames() : array;


    /**
     * @return bool
     */
    public function isLastTable() : bool;


    /**
     * @return string
     */
    public function name() : string;


    /**
     * @return int
     */
    public function interval() : int;


    /**
     * @return array
     */
    public function toArray() : array;

}
