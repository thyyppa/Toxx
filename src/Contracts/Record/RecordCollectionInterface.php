<?php namespace Hyyppa\Toxx\Contracts\Record;

interface RecordCollectionInterface
{

    /**
     * @param  FrameInterface  $frame
     *
     * @return RecordCollectionInterface
     */
    public function addRecord(FrameInterface $frame) : RecordCollectionInterface;

}
