<?php namespace Hyyppa\Toxx\Exceptions;

class MissingFieldsException extends RuntimeException
{

    public function __construct($type = 'csv')
    {
        parent::__construct(
            'If this is a plain '.$type.' file, be sure to include a list of field names: Toxx::load($filename,[\'list\',\'field\',\'names\',\'here\'])'
        );
    }

}
