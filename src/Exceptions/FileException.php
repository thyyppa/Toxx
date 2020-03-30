<?php namespace Hyyppa\Toxx\Exceptions;

class FileException extends RuntimeException
{

    /**
     * @param  string       $path
     * @param  string|null  $message
     * @param  string|null  $name
     */
    public function __construct(string $path, string $message = null, string $name = null)
    {
        $name = $name ? $name.' file ' : 'File ';

        parent::__construct(
            sprintf(
                '%s%s: `%s`',
                $name,
                $message,
                $path
            )
        );
    }

}
