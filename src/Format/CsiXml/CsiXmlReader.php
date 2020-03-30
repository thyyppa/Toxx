<?php namespace Hyyppa\Toxx\Format\CsiXml;

use Hyyppa\Toxx\Exceptions\CsiXmlException;
use Hyyppa\Toxx\Utils\Collection;
use Hyyppa\Toxx\Utils\FileSystem;
use SimpleXMLElement;
use XMLReader;

class CsiXmlReader
{

    /**
     * @param       $xml_filename
     * @param       $xsd_filename
     * @param  int  $check_records
     *
     * @return bool
     */
    public static function validateWithXsd($xml_filename, $xsd_filename, int $check_records = 10) : bool
    {

        FileSystem::AssertNotEmpty($xsd_filename, 'Schema');


        $reader = XMLReader::open($xml_filename);
        $reader->setSchema($xsd_filename);

        $record = 0;
        static::readUntil($reader, static function ($reader) use (&$record, $check_records) {
            if ($reader->name === 'r' && $reader->nodeType === XMLREADER::ELEMENT) {
                $record++;
            }

            return ! $reader->isValid() || $record >= $check_records;
        });

        $valid = $reader->isValid();
        $reader->close();
        unset($reader);

        return $valid;
    }


    /**
     * @param  string  $filename
     *
     * @return string
     */
    public static function getVersion(string $filename) : string
    {
        $reader = XMLReader::open($filename);

        static::readUntil($reader, static function (XMLReader $reader) {
            return $reader->name === 'csixml' && $reader->nodeType === XMLREADER::ELEMENT;
        });

        $version = $reader->getAttribute('version');

        $reader->close();
        unset($reader);

        return $version;
    }


    /**
     * @param  XMLReader  $reader
     * @param  callable   $callback
     *
     * @return XMLReader
     */
    public static function readUntil(XMLReader &$reader, callable $callback) : XMLReader
    {
        while ($reader->read() && ! $callback($reader)) {
        }

        return $reader;
    }


    /**
     * @param  string  $filename
     * @param  string  $tag
     *
     * @return array
     */
    public static function tagContents(string $filename, string $tag) : array
    {
        $reader = XMLReader::open($filename);

        static::readUntil($reader, static function (XMLReader $reader) use ($tag) {
            return $reader->name === $tag && $reader->nodeType === XMLREADER::ELEMENT;
        });

        $xml = $reader->readOuterXml();
        $reader->close();
        unset($reader);

        if ( ! $xml) {
            throw new CsiXmlException("Could not find tag '$tag' in file '$filename'.");
        }

        $xml   = new SimpleXMLElement($xml);
        $array = json_decode(json_encode($xml), true);
        unset($xml);

        return $array;
    }


    /**
     * @param  string  $filename
     *
     * @return int
     */
    public static function recordCount(string $filename) : int
    {
        $reader = XMLReader::open($filename);
        $count  = 0;

        static::readUntil($reader, static function (XMLReader $reader) {
            return $reader->name === 'r' && $reader->nodeType === XMLREADER::ELEMENT;
        });

        while ($reader->next()) {
            if ($reader->name === 'r' && $reader->nodeType === XMLREADER::ELEMENT) {
                $count++;
            }
        }

        $reader->close();
        unset($reader);

        return $count;
    }


    /**
     * @param  string  $filename
     * @param  int     $offset
     * @param  int     $count
     *
     * @return array
     */
    public static function readFromOffset(string $filename, int $offset = 0, int $count = 1) : array
    {
        $reader  = XMLReader::open($filename);
        $_count  = 0;
        $_offset = 0;
        $records = new Collection();

        static::readUntil($reader, static function (XMLReader $reader) use (&$_offset, $offset) {
            if ($reader->name === 'r'
                && $reader->nodeType === XMLREADER::ELEMENT
                && $_offset++ >= $offset
            ) {
                return true;
            }
        });

        do {
            if ($reader->name === 'r' && $reader->nodeType === XMLREADER::ELEMENT) {
                $records->push((array) (new SimpleXMLElement($reader->readOuterXml())));
                $_count++;
            }
        } while ($reader->next() && $_count < $count);

        $reader->close();
        unset($reader);

        return $records->map(function ($record) {
            $attr = array_shift($record);

            if ($attr[ 'no' ]) {
                $record = array_merge([
                    'RECORD' => $attr[ 'no' ],
                ], $record);
            }

            if ($attr[ 'time' ]) {
                $record = array_merge([
                    'TIMESTAMP' => $attr[ 'time' ],
                ], $record);
            }

            return array_values($record);
        })->values()->toArray();
    }

}
