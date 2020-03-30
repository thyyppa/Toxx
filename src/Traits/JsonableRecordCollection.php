<?php namespace Hyyppa\Toxx\Traits;

use Hyyppa\Toxx\Records\Record;
use const JSON_PRETTY_PRINT;

trait JsonableRecordCollection
{

    /**
     * @param  int  $options
     *
     * @return string
     */
    public function jsonWithUnits($options = JSON_PRETTY_PRINT) : string
    {
        $this->each(static function (Record $record) use ($options) {
            return $record->jsonWithUnits($options);
        });

        return $this->json($options);
    }


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function jsonWithHidden($options = JSON_PRETTY_PRINT) : string
    {
        $this->each(static function (Record $record) use ($options) {
            return $record->jsonWithHidden($options);
        });

        return $this->json($options);
    }


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function json($options = JSON_PRETTY_PRINT) : string
    {
        return json_encode($this, $options);
    }

}
