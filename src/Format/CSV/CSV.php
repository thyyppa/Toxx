<?php namespace Hyyppa\Toxx\Format\CSV;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Format\General\BaseDataFile;
use Hyyppa\Toxx\Records\Records;
use SplFileObject;
use const PHP_INT_MAX;

class CSV extends BaseDataFile
{


    /**
     * @param  string             $filename
     * @param  array              $field_names
     * @param  SettingsInterface  $settings
     */
    public function __construct(string $filename, array $field_names, SettingsInterface $settings)
    {
        $this->_settings = $settings;

        $this->_file = new SplFileObject($filename, 'r');
        $this->_file->setFlags(
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );
        $this->_file->setMaxLineLen(2000);

        $this->_header = $this->parseHeader($field_names);
    }


    /**
     * @param  array  $fields
     *
     * @return FileHeaderInterface
     */
    protected function parseHeader(array $fields) : FileHeaderInterface
    {
        $header = new CSVFileHeader($this->_settings);

        array_unshift($fields, 'TABLE', '_YEAR_', '_DAY_', '_TIME_', '_SECONDS_');
        $header->setFields($fields);
        $header->setSize(0);

        return $header;
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface
     */
    public function read(int $count = 1) : RecordCollectionInterface
    {
        if (($this->currentLine() + $count) > $this->totalLines()) {
            return $this->last($count);
        }

        $records = Records::withHeader($this->_header);

        for ($i = 0; $i < $count; $i++) {

            $records->addRecord(
                new CSVFrame(
                    $this->currentCsv(),
                    $this->_header,
                    $this->currentLine()
                )
            );

            $this->_file->next();
        }

        return $records;
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function first(int $count = 1)
    {
        $this->seekFrame(0);

        $read = $this->read($count);

        return $count === 1 ? $read->first() : $read;
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function last(int $count = 1)
    {
        $this->seekEndFrames($count);

        $read = $this->read($count);

        return $count === 1 ? $read->first() : $read;
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function next(int $count = 1)
    {
        $read = $this->read($count);

        return $count === 1 ? $read->first() : $read;
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function prev(int $count = 1)
    {
        $start = $this->keepWithinBounds(
            $this->currentLine() - $count - 2
        );

        $this->_file->seek($start);
        $read = $this->read($count);

        return $count === 1 ? $read->first() : $read;
    }


    /**
     * @return RecordCollectionInterface
     */
    public function all() : RecordCollectionInterface
    {
        return $this->seekFrame(0)->read($this->count());
    }


    /**
     * @param $start
     * @param $end
     *
     * @return RecordCollectionInterface
     */
    public function dateRange($start, $end) : RecordCollectionInterface
    {
        return $this->frameRange(
            $this->findDateFrameIndex($start),
            $this->findDateFrameIndex($end)
        );
    }


    /**
     * @return int
     */
    public function count() : int
    {
        if ($this->_count !== null) {
            return $this->_count;
        }

        return $this->_count = $this->totalLines() - $this->header()->size();
    }


    /**
     * @param  int  $per_page
     *
     * @return int
     */
    public function pageCount(int $per_page = 60) : int
    {
        return ceil($this->count() / $per_page);
    }


    /**
     * @return int
     */
    public function size() : int
    {
        return $this->_file->getSize();
    }


    /**
     * @return int
     */
    protected function totalLines() : int
    {
        if ($this->_total_lines !== null) {
            return $this->_total_lines;
        }

        $old_ptr = $this->_file->ftell();

        $this->_file->seek(PHP_INT_MAX);
        $last_line = $this->_file->key();

        $this->_file->fseek($old_ptr);

        return $this->_total_lines = $last_line;
    }


    /**
     * @return array
     */
    protected function currentCsv() : array
    {
        return $this->csvToArray($this->_file->current());
    }


    /**
     * @return int
     */
    protected function currentLine() : int
    {
        return $this->_file->key();
    }


    /**
     * @return int
     */
    protected function currentFrame() : int
    {
        return $this->currentLine() - $this->header()->size();
    }


    /**
     * @param  int  $line
     *
     * @return DataFileInterface
     */
    protected function seek(int $line) : DataFileInterface
    {
        $this->_file->seek($line);

        return $this;
    }


    /**
     * @param  int  $index
     *
     * @return DataFileInterface
     */
    protected function seekFrame(int $index) : DataFileInterface
    {
        $line = $index + $this->header()->size();

        if ($line < $this->header()->size()) {
            $line = $this->header()->size();
        }

        if ($line > $this->totalLines()) {
            $line = $this->totalLines();
        }

        return $this->seek($line);
    }


    /**
     * @param  int  $count
     *
     * @return DataFileInterface
     */
    protected function seekEndFrames(int $count = 1) : DataFileInterface
    {
        return $this->seekFrame($this->count() - $count);
    }


    /**
     * @return string
     */
    protected function fgets() : string
    {
        return trim($this->_file->fgets());
    }


    /**
     * @return array
     */
    protected function fgetcsv() : array
    {
        return $this->_file->fgetcsv();
    }

}
