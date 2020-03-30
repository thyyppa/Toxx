<?php namespace Hyyppa\Toxx\Format\CsiXml;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Format\General\FileHeader;

class CsiXmlFileHeader extends FileHeader
{

    /**
     * @var int
     */
    protected $_count = 0;


    /**
     * @param  array  $_fields
     *
     * @return FileHeaderInterface
     */
    public function setFields(array $_fields) : FileHeaderInterface
    {
        $this->_fields = array_merge([
            'TIMESTAMP',
            'RECORD',
        ], $_fields);

        return $this;
    }


    /**
     * @param  array  $_types
     *
     * @return FileHeaderInterface
     */
    public function setTypes(array $_types) : FileHeaderInterface
    {
        $this->_types = array_merge([
            'CARBON',
            'RECORD',
        ], $_types);

        return $this;
    }


    /**
     * @param  array  $_units
     *
     * @return FileHeaderInterface
     */
    public function setUnits(array $_units) : FileHeaderInterface
    {
        $this->_units = array_merge([
            'TS',
            'RN',
        ], $_units);

        return $this;
    }


    /**
     * @param  array  $processing
     *
     * @return FileHeaderInterface
     */
    public function setProcessing(array $processing) : FileHeaderInterface
    {
        $this->_processing = array_merge([
            'Smp',
            'Smp',
        ], $processing);

        return $this;
    }


    /**
     * @param  int  $count
     *
     * @return FileHeader
     */
    public function setCount(int $count) : FileHeader
    {
        $this->_count = $count;

        return $this;
    }


    /**
     * @param  null  $offset
     *
     * @return mixed
     */
    public function types($offset = null)
    {
        if ( ! $this->_types) {
            throw new UndefinedTypeException('No types defined.');
        }

        if ($offset !== null) {
            if ( ! isset($this->_types[ $offset ])) {
                throw new UndefinedTypeException('No types defined at frame offset '.$offset);
            }

            return $this->_types[ $offset ];
        }

        return $this->_types;
    }


    /**
     * @return int
     */
    public function count() : int
    {
        return $this->_count;
    }

}
