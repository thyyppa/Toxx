<?php namespace Hyyppa\Toxx\Traits;

use const JSON_PRETTY_PRINT;

trait JsonableRecord
{

    /**
     * @var
     */
    protected $_forJson;


    /**
     * @param  array|null  $array
     *
     * @return array
     */
    protected function forJson(array $array = null) : array
    {
        $this->_forJson = $array ?? $this->_forJson;

        return $this->_forJson ?? $this->simple();
    }


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function jsonWithUnits($options = JSON_PRETTY_PRINT) : string
    {
        $this->_forJson = $this->human();

        return json_encode($this->_forJson, $options);
    }


    /**
     * @param  int  $options
     *
     * @return string
     */
    public function jsonWithHidden($options = JSON_PRETTY_PRINT) : string
    {
        $this->_forJson = $this->withHidden()->simple();

        return json_encode($this->_forJson, $options);
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


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->forJson();
    }

}
