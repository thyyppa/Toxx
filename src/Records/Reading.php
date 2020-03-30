<?php namespace Hyyppa\Toxx\Records;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Reading\ReadingInterface;
use Hyyppa\Toxx\Traits\LazyAccessor;
use Jawira\CaseConverter\CaseConverterException;

class Reading implements ReadingInterface
{

    use LazyAccessor, ReadingMutations;

    /**
     * @var FileHeaderInterface
     */
    protected $_header;

    /**
     * @var string
     */
    protected $_field;

    /**
     * @var mixed
     */
    protected $_value;

    /**
     * @var string|null
     */
    protected $_type;

    /**
     * @var string|null
     */
    protected $_unit;

    /**
     * @var string|null
     */
    protected $_processing;


    /**
     * @param                       $value
     * @param                       $index
     * @param  FileHeaderInterface  $header
     */
    public function __construct($value, $index, FileHeaderInterface &$header)
    {
        $this->_value  = $value;
        $this->_header = $header;

        if (is_string($index)) {
            $this->setField($index);

            return;
        }

        $this
            ->setField($this->header()->field($index))
            ->setType($this->header()->type($index))
            ->setUnit($this->header()->unit($index))
            ->setProcessing($this->header()->process($index));
    }


    /**
     * @param  string  $_field
     *
     * @return $this
     */
    public function setField(string $_field) : ReadingInterface
    {
        $this->_field = $_field;

        return $this;
    }


    /**
     * @param  string|null  $_type
     *
     * @return ReadingInterface
     */
    public function setType(string $_type = null) : ReadingInterface
    {
        $this->_type = $_type;

        return $this;
    }


    /**
     * @param  string|null  $_unit
     *
     * @return ReadingInterface
     */
    public function setUnit(string $_unit = null) : ReadingInterface
    {
        $this->_unit = $_unit;

        return $this;
    }


    /**
     * @param  string|null  $_processing
     *
     * @return ReadingInterface
     */
    public function setProcessing(string $_processing = null) : ReadingInterface
    {
        $this->_processing = $_processing;

        return $this;
    }


    /**
     * @return FileHeaderInterface
     */
    public function header() : FileHeaderInterface
    {
        return $this->_header;
    }


    /**
     * @return string
     * @throws CaseConverterException
     */
    public function field() : string
    {
        return $this->fieldMutation($this->_field);
    }


    /**
     * @return string
     */
    public function originalField() : string
    {
        return $this->_field;
    }


    /**
     * @return mixed
     */
    public function rawValue()
    {
        return $this->_value;
    }


    /**
     * @return mixed
     */
    public function value()
    {
        return $this->readingMutation($this->_value);
    }


    /**
     * @return string|null
     */
    public function type() : ?string
    {
        return $this->_type;
    }


    /**
     * @return string|null
     */
    public function unit() : ?string
    {
        return $this->_unit;
    }


    /**
     * @return string|null
     */
    public function processing() : ?string
    {
        return $this->_processing;
    }


    /**
     * @return array
     * @throws CaseConverterException
     */
    public function info() : array
    {
        return [
            'field'      => $this->field(),
            'value'      => $this->value(),
            'type'       => $this->type(),
            'unit'       => $this->unit(),
            'processing' => $this->processing(),
        ];
    }


    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->_value;
    }

}
