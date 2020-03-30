<?php namespace Hyyppa\Toxx\Contracts;

interface ToxxInterface
{

    /**
     * @param  string       $filename
     * @param  string|null  $table
     *
     * @return ToxxInterface
     */
    public function tdf(string $filename, string $table = null) : ToxxInterface;


    /**
     * @param  string  $table
     *
     * @return ToxxInterface
     */
    public function table(string $table) : ToxxInterface;


    /**
     * @param  array  $fields
     *
     * @return ToxxInterface
     */
    public function fields(array $fields) : ToxxInterface;


    /**
     * @param  mixed  ...$params
     *
     * @return DataFileInterface
     */
    public static function load(...$params) : DataFileInterface;

}
