<?php namespace Hyyppa\Toxx\Exceptions;

class InvalidXmlSchemaException extends RuntimeException
{

    public function __construct(string $xml_filename, string $schema_filename)
    {
        parent::__construct(
            sprintf(
                'The xml file \'%s\' does not conform to schema \'%s\'.',
                $xml_filename,
                $schema_filename
            )
        );
    }
}
