<?php namespace Hyyppa\Toxx\Format\General;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Reading\ReadingCollectionInterface;
use Hyyppa\Toxx\Contracts\Record\FrameInterface;
use Hyyppa\Toxx\Contracts\Record\RecordInterface;
use Hyyppa\Toxx\Records\Readings;
use Hyyppa\Toxx\Records\Record;

abstract class Frame implements FrameInterface
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $line_number;

    /**
     * @var FileHeaderInterface
     */
    protected $header;


    /**
     * @param  array                $data
     * @param  FileHeaderInterface  $header
     * @param  null                 $line_number
     */
    public function __construct(array $data, FileHeaderInterface &$header = null, $line_number = null)
    {
        $this->data = $data;
        $this->header = $header;
        $this->line_number = $line_number;
    }


    /**
     * @param  FileHeaderInterface  $header
     *
     * @return FrameInterface
     */
    public function setHeader(FileHeaderInterface &$header = null) : FrameInterface
    {
        $this->header = $header;

        return $this;
    }


    /**
     * @return ReadingCollectionInterface
     */
    public function readings() : ReadingCollectionInterface
    {
        $readings = Readings::withHeader($this->header);

        foreach ($this->data as $index => $value) {
            $readings->addReading($value, $index);
        }

        return $readings;
    }


    /**
     * @return array
     */
    public function raw() : array
    {
        return $this->data;
    }


    /**
     * @return RecordInterface
     */
    public function asRecord() : RecordInterface
    {
        return new Record($this->readings(), $this->header, $this->line_number);
    }


    /**
     * @return array
     */
    public function asArray() : array
    {
        return array_combine($this->header->fields(), $this->data);
    }

}
