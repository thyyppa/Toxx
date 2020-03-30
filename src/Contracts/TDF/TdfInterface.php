<?php namespace Hyyppa\Toxx\Contracts\TDF;

use Hyyppa\Toxx\TableDefinition\Structure\Table;
use Hyyppa\Toxx\TableDefinition\Structure\Tables;

interface TdfInterface
{

    /**
     * @param  string  $filename
     *
     * @return TdfInterface
     */
    public static function load(string $filename) : TdfInterface;


    /**
     * Get all table definitions.
     *
     * @return Tables
     */
    public function all() : Tables;


    /**
     * Get named table.
     *
     * @param  string  $name
     *
     * @return Table|null
     */
    public function get(string $name) : ?Table;


    /**
     * Check if table exists in definition.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function has(string $name) : bool;

}
