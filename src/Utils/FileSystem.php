<?php namespace Hyyppa\Toxx\Utils;

use Hyyppa\Toxx\Exceptions\FileException;

class FileSystem
{


    /**
     * @param  string  $path
     * @param  string  $name
     *
     * @return bool
     *
     * @throws FileException
     */
    public static function AssertExists(string $path, string $name = '') : bool
    {
        if ( ! file_exists($path)) {
            throw new FileException($path, 'not found', $name);
        }

        return true;
    }


    /**
     * @param  string  $path
     * @param  string  $name
     *
     * @return bool
     *
     * @throws FileException
     */
    public static function AssertNotEmpty(string $path, string $name = '') : bool
    {
        static::AssertExists($path, $name);

        if (filesize($path) <= 0) {
            throw new FileException($path, 'exists, but is empty', $name);
        }

        return true;
    }

}
