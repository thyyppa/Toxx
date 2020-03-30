<?php namespace Hyyppa\Toxx\Format\CsiXml;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Contracts\Record\RecordInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Exceptions\CsiXmlException;
use Hyyppa\Toxx\Exceptions\InvalidXmlSchemaException;
use Hyyppa\Toxx\Format\General\BaseDataFile;
use Hyyppa\Toxx\Records\Records;

class CsiXml extends BaseDataFile
{


    /**
     * @var string
     */
    protected $_filename;

    /**
     * @var int
     */
    private $_current = 0;


    /**
     * @param  string             $filename
     * @param  SettingsInterface  $settings
     */
    public function __construct(string $filename, SettingsInterface $settings)
    {
        $this->_filename = $filename;
        $this->_settings = $settings;

        if ( ! CsiXmlReader::validateWithXsd($this->_filename, $this->asset('csixml.xsd'))) {
            throw new InvalidXmlSchemaException($this->_filename, $this->asset('csixml.xsd'));
        }

        if (($version = CsiXmlReader::getVersion($this->_filename)) !== '1.0') {
            throw new CsiXmlException("CSIXML version '$version' not supported, only version '1.0'.");
        }

        $this->_header = $this->parseHeader($this->_filename);
    }


    /**
     * @param  string  $filename
     *
     * @return CsiXmlFileHeader
     */
    protected function parseHeader(string $filename) : CsiXmlFileHeader
    {
        $header  = new CsiXmlFileHeader($this->_settings);
        $fields  = [];
        $units   = [];
        $process = [];
        $types   = [];

        $header_fields = CsiXmlReader::tagContents($filename, 'fields')[ 'field' ];
        foreach ($header_fields as $k => [ '@attributes' => $field ]) {
            $fields[ $k ]  = $field[ 'name' ];
            $types[ $k ]   = $this->convertType($field[ 'type' ]);
            $units[ $k ]   = $field[ 'units' ] ?? '';
            $process[ $k ] = $field[ 'process' ] ?? '';
        }

        $header->setFields($fields)
               ->setUnits($units)
               ->setProcessing($process)
               ->setTypes($types);

        $environment = CsiXmlReader::tagContents($filename, 'environment');
        $header->setInfo([
            'format'        => 'CSIXML',
            'station'       => $environment[ 'station-name' ],
            'datalogger'    => $environment[ 'model' ],
            'serial_number' => $environment[ 'serial-no' ],
            'os_version'    => $environment[ 'os-version' ],
            'dld_name'      => $environment[ 'dld-name' ],
            'dld_signature' => $environment[ 'dld-sig' ],
            'table'         => $environment[ 'table-name' ],
        ]);

        $header->setCount(
            CsiXmlReader::recordCount($filename)
        )->setSize(
            filesize($filename)
        );

        return $header;
    }


    /**
     * todo: these don't match up quite right
     *
     * @param  string  $type
     *
     * @return string
     */
    protected function convertType(string $type) : string
    {
        switch ($type) {
            case('xsd:string'):
                return 'ASCII';
            case('xsd:int'):
            case('xsd:long'):
                return 'LONG';
            case('xsd:unsignedInt'):
            case('xsd:unsignedLong'):
                return 'ULONG';
            case('xsd:short'):
                return 'FP2';
            case('xsd:unsignedByte'):
            case('xsd:unsignedShort'):
                return 'USHORT';
            case('xsd:byte'):
                return 'SHORT';
            case('xsd:float'):
                return 'IEEE4';
            case('xsd:double'):
                return 'IEEE8';
            case('xsd:boolean'):
                return 'BOOL';
            case('xsd:dateTime'):
                return 'CARBON';
            default:
                return $type;
        }
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface
     */
    public function read(int $count = 1) : RecordCollectionInterface
    {
        if ($this->_current + $count > $this->count()) {
            $count = $this->count() - $count;
        }

        $readings = CsiXmlReader::readFromOffset($this->_filename, $this->_current, $count);
        $records  = Records::withHeader($this->_header);

        foreach ($readings as $reading) {
            $records->addRecord(
                new CsiXmlFrame($reading, $this->_header)
            );
        }

        $this->_current += $records->count();

        return $records;
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface|RecordInterface
     */
    public function first(int $count = 1)
    {
        $this->seekFrame(0);

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface|RecordInterface
     */
    public function last(int $count = 1)
    {
        $this->seekFrame($this->count() - $count + 1);

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface|RecordInterface
     */
    public function next(int $count = 1)
    {
        $this->seekFrame($this->_current);

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface|RecordInterface
     */
    public function prev(int $count = 1)
    {
        $this->seekFrame($this->_current - ($count * 2));

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @return RecordCollectionInterface
     */
    public function all() : RecordCollectionInterface
    {
        return $this->first($this->count());
    }


    /**
     * @param $start
     * @param $end
     *
     * @return RecordCollectionInterface
     */
    public function dateRange($start, $end) : RecordCollectionInterface
    {
        return $this->frameRange(
            $this->findDateFrameIndex($start),
            $this->findDateFrameIndex($end)
        );
    }


    /**
     * @param  int  $per_page
     *
     * @return int
     */
    public function pageCount(int $per_page = 60) : int
    {
        return ceil($this->count() / $per_page);
    }


    /**
     * @inheritDoc
     */
    public function count() : int
    {
        return $this->header()->count();
    }


    /**
     * @inheritDoc
     */
    public function size() : int
    {
        return $this->header()->size();
    }


    /**
     * @param  int  $index
     *
     * @return DataFileInterface
     */
    protected function seekFrame(int $index) : DataFileInterface
    {
        $this->_current = $this->keepWithinBounds($index);

        return $this;
    }


}
