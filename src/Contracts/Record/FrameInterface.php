<?php namespace Hyyppa\Toxx\Contracts\Record;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Reading\ReadingCollectionInterface;

interface FrameInterface
{

    /**
     * @return ReadingCollectionInterface
     */
    public function readings() : ReadingCollectionInterface;


    /**
     * @return RecordInterface
     */
    public function asRecord() : RecordInterface;


    /**
     * @return array
     */
    public function asArray() : array;


    /**
     * @return array
     */
    public function raw() : array;


    /**
     * @param  FileHeaderInterface  $header
     *
     * @return FrameInterface
     */
    public function setHeader(FileHeaderInterface &$header = null) : FrameInterface;

}
