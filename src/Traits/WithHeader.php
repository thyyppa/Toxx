<?php namespace Hyyppa\Toxx\Traits;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;

trait WithHeader
{

    /**
     * @var FileHeaderInterface
     */
    protected $_header;


    /**
     * @param  FileHeaderInterface  $header
     *
     * @return static
     */
    public static function withHeader(FileHeaderInterface &$header) : self
    {
        $instance = new static();
        $instance->setHeader($header);

        return $instance;
    }


    /**
     * @param  FileHeaderInterface  $header
     *
     * @return $this
     */
    public function setHeader(FileHeaderInterface &$header) : self
    {
        $this->_header = $header;

        return $this;
    }


    /**
     * @return FileHeaderInterface
     */
    public function header() : FileHeaderInterface
    {
        return $this->_header;
    }

}
