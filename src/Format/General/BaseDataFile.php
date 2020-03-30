<?php namespace Hyyppa\Toxx\Format\General;

use Carbon\Carbon;
use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Records\Record;
use Hyyppa\Toxx\Records\Records;
use Hyyppa\Toxx\Traits\HasSettings;
use SplFileObject;

abstract class BaseDataFile implements DataFileInterface
{

    use HasSettings;

    protected const CHOOSE_HIGHER = 0;
    protected const CHOOSE_LOWER  = -1;

    /**
     * @var FileHeader
     */
    protected $_header;

    /**
     * @var int|null
     */
    protected $_count;

    /**
     * @var resource|SplFileObject
     */
    protected $_file;

    /**
     * @var int|null
     */
    protected $_total_lines;


    /**
     * @return FileHeaderInterface
     */
    public function header() : FileHeaderInterface
    {
        return $this->_header;
    }


    /**
     * @return array|null
     */
    public function info() : ?array
    {
        return $this->header()->info();
    }


    /**
     * @return array|null
     */
    public function fields() : ?array
    {
        return $this->header()->fields();
    }


    /**
     * @param  string  $csv
     *
     * @return array
     */
    protected function csvToArray(string $csv) : array
    {
        $csv = trim($csv);
        $csv = str_replace('"', '', $csv);
        $csv = str_replace("\r", '', $csv);

        return explode(',', $csv);
    }


    /**
     * @param  int  $seconds
     *
     * @return string
     */
    protected function secondsToDate(int $seconds) : string
    {
        return Carbon::parse('1990-01-01 00:00:00')->addSeconds($seconds)->toString();
    }


    /**
     * @param $date
     *
     * @return int
     */
    protected function dateToSeconds($date) : int
    {
        if ( ! $date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->diffInSeconds(
            Carbon::parse('1990-01-01 00:00:00')
        );
    }


    /**
     * @param  int  $index
     *
     * @return int
     */
    protected function keepWithinBounds(int $index) : int
    {
        return max([0, min([$this->count(), $index])]);
    }


    /**
     * @param  int  $a
     * @param  int  $b
     *
     * @return array
     */
    protected function sortLowHigh(int $a, int $b) : array
    {
        return [min([$a, $b]), max([$a, $b])];
    }


    /**
     * @param  int  $start
     * @param  int  $end
     *
     * @return Records
     */
    protected function frameRange(int $start, int $end) : Records
    {
        [$start, $end] = $this->sortLowHigh($start, $end);

        return $this->seekFrame($start)->read($end - $start + 1);
    }


    /**
     * @param  int  $index
     *
     * @return Record
     */
    protected function getRecordAtIndex(int $index) : Record
    {
        return $this->seekFrame(
            $this->keepWithinBounds($index)
        )->read()->first();
    }


    /**
     * @param  int  $page
     * @param  int  $per_page
     *
     * @return RecordCollectionInterface
     */
    public function page(int $page, int $per_page = 60) : RecordCollectionInterface
    {
        $per_page    = $per_page >= 1 ? $per_page : 1;
        $page_count  = $this->pageCount($per_page);
        $page        = $page >= 1 ? $page : 1;
        $page        = $page < $page_count ? $page : $page_count;
        $start_frame = ($page - 1) * $per_page;

        if ($start_frame + $per_page > $this->count()) {
            $start_frame = $this->count() - $per_page;
        }

        return $this->seekFrame($start_frame)->read($per_page);
    }


    /**
     * @param  string|Carbon  $date
     *
     * @param  int            $choose
     *
     * @return int Frame index of closest match
     */
    protected function findDateFrameIndex($date, int $choose = -1) : int
    {

        // date is stored in seconds since 1990-01-01
        $date = $this->dateToSeconds($date);

        // date is before the date of the first record, just return first index
        if ($date < $this->first()->seconds) {
            return 0;
        }

        // date is after the date of the last record, just return last index
        if ($date > $this->last()->seconds) {
            return $this->count();
        }

        $lower_bound = 0;
        $upper_bound = $this->count();
        $pivot_index = $lower_bound + floor($upper_bound / 2);

        while (true) {

            // date of pivot record
            $pivot_date = $this->getRecordAtIndex($pivot_index)->seconds;

            // date of record directly before
            $date_before = $this->getRecordAtIndex($pivot_index - 1)->seconds;

            // date of record directly after
            $date_after = $this->getRecordAtIndex($pivot_index + 1)->seconds;

            // hurray!
            if ($pivot_date === $date) {
                return $pivot_index;
            }

            // we're stuck directly between two dates, taker higher or lower depending on $choose
            if ($date < $date_after && $date > $date_before) {
                return $pivot_index + $choose;
            }

            // date higher, set lower boundary to current pivot
            if ($pivot_date < $date) {
                $lower_bound = $pivot_index;
            }

            // date lower, set upper boundary to current pivot
            if ($pivot_date > $date) {
                $upper_bound = $pivot_index;
            }

            // set pivot to the midpoint between lower and upper bounds
            $pivot_index = $lower_bound + floor(($upper_bound - $lower_bound) / 2);

        }

    }


    /**
     * Get absolute path to file in assets directory.
     *
     * @param  string  $filename
     *
     * @return string
     */
    protected function asset(string $filename) : string
    {
        return realpath(__DIR__.'/../../../assets/'.$filename);
    }


}
