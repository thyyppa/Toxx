<?php namespace Hyyppa\Toxx\Contracts;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Contracts\Record\RecordInterface;

interface DataFileInterface
{

    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface
     */
    public function read(int $count = 1) : RecordCollectionInterface;


    /**
     * @param  int  $count
     *
     * @return RecordInterface|RecordCollectionInterface
     */
    public function first(int $count = 1);


    /**
     * @param  int  $count
     *
     * @return RecordInterface|RecordCollectionInterface
     */
    public function last(int $count = 1);


    /**
     * @param  int  $count
     *
     * @return RecordInterface|RecordCollectionInterface
     */
    public function next(int $count = 1);


    /**
     * @param  int  $count
     *
     * @return RecordInterface|RecordCollectionInterface
     */
    public function prev(int $count = 1);


    /**
     * @param  int  $page
     * @param  int  $per_page
     *
     * @return RecordCollectionInterface
     */
    public function page(int $page, int $per_page = 60) : RecordCollectionInterface;


    /**
     * @return RecordCollectionInterface
     */
    public function all() : RecordCollectionInterface;


    /**
     * @param $start
     * @param $end
     *
     * @return RecordCollectionInterface
     */
    public function dateRange($start, $end) : RecordCollectionInterface;


    /**
     * @param  int  $per_page
     *
     * @return int
     */
    public function pageCount(int $per_page = 60) : int;


    /**
     * @return int
     */
    public function count() : int;


    /**
     * @return FileHeaderInterface|null
     */
    public function header() : ?FileHeaderInterface;


    /**
     * @return int
     */
    public function size() : int;


    /**
     * @return array|null
     */
    public function info() : ?array;


    /**
     * @return array|null
     */
    public function fields() : ?array;


    /**
     * @param  SettingsInterface|null  $settings
     *
     * @return SettingsInterface
     */
    public function settings(SettingsInterface &$settings = null) : SettingsInterface;

}
