<?php namespace Hyyppa\Toxx\Exceptions;

class UnknownFormatException extends RuntimeException
{

    public function __construct(string $filename)
    {
        parent::__construct('Unknown file format for file: '.$filename);
    }

}
