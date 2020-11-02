<?php namespace Hyyppa\Toxx\Records;

use Hyyppa\Toxx\Contracts\JsonAndArrayOutput;
use Hyyppa\Toxx\Contracts\Record\FrameInterface;
use Hyyppa\Toxx\Contracts\Record\JsonableRecordInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Contracts\Record\RecordInterface;
use Hyyppa\Toxx\Format\General\Collection;
use Hyyppa\Toxx\Traits\JsonableRecordCollection;

class Records extends Collection implements RecordCollectionInterface, JsonableRecordInterface, JsonAndArrayOutput
{

    use JsonableRecordCollection;


    /**
     * @param  FrameInterface  $frame
     *
     * @return RecordCollectionInterface
     */
    public function addRecord(FrameInterface $frame) : RecordCollectionInterface
    {
        return $this->push(
            $frame->setHeader($this->_header)->asRecord()
        );
    }


    /**
     * @param  mixed  $fields
     *
     * @return Records
     */
    public function only($fields) : RecordCollectionInterface
    {
        if ( ! is_array($fields)) {
            $fields = [$fields];
        }

        return $this->map(function (RecordInterface $record) use ($fields) {
            return $record->only($fields);
        });
    }


    /**
     * @return array
     */
    public function simple() : array
    {
        return $this->mapWithKeys(
            static function (RecordInterface $record) {
                return [$record->arrayKey() => $record->simple()]; // todo: settings option to use timestamp or seconds for key
            }
        )->toArray();
    }


    /**
     * @return array
     */
    public function human() : array
    {
        return $this->mapWithKeys(
            static function (RecordInterface $record) {
                return [$record->arrayKey() => $record->human()];
            }
        )->toArray();
    }


    /**
     * @return array
     */
    public function arrayWithUnits() : array
    {
        return $this->human();
    }


    /**
     * @return array
     */
    public function array() : array
    {
        return $this->map(static function (RecordInterface $record) {
            return $record->array();
        })->toArray();
    }


    /**
     * @return array
     */
    public function arrayWithHidden() : array
    {
        return $this->map(static function (Record $record) {
            return $record->arrayWithHidden();
        })->array();
    }

}
