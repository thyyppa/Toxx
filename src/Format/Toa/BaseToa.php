<?php namespace Hyyppa\Toxx\Format\Toa;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Format\General\BaseDataFile;
use Hyyppa\Toxx\Records\Records;
use SplFileObject;
use const PHP_INT_MAX;

abstract class BaseToa extends BaseDataFile
{

    /**
     * @param $file
     *
     * @return FileHeaderInterface
     */
    abstract protected function parseHeader($file) : FileHeaderInterface;


    /**
     * @param  string             $filename
     * @param  SettingsInterface  $settings
     */
    public function __construct(string $filename, SettingsInterface $settings)
    {
        $this->_settings = $settings;

        $this->_file = new SplFileObject($filename, 'r');
        $this->_file->setFlags(
            SplFileObject::READ_AHEAD &&
            SplFileObject::SKIP_EMPTY &&
            SplFileObject::DROP_NEW_LINE
        );

        $this->_header = $this->parseHeader($this->_file);
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface
     */
    public function read(int $count = 1) : RecordCollectionInterface
    {
        if ($this->currentLine() + $count > $this->totalLines()) {
            $count = $this->totalLines() - $this->currentLine();
        }

        $records = Records::withHeader($this->_header);

        for ($i = 0; $i < $count; $i++) {
            $records->addRecord(
                new ToaFrame($this->fgetcsv(), $this->_header)
            );
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

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function last(int $count = 1)
    {
        $this->seekEndFrames($count);

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function next(int $count = 1)
    {
        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return mixed|void
     */
    public function prev(int $count = 1)
    {
        $start_frame = $this->keepWithinBounds(
            $this->currentFrame() - $count
        );

        $this->seekFrame($start_frame);

        if ($count === 1) {
            return $this->read()->first();
        }

        return $this->read($count);
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

        $current = $this->currentLine();

        $this->seek(PHP_INT_MAX);
        $last_line = $this->currentLine();

        $this->seek($current);

        return $this->_total_lines = $last_line;
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
     * @return $this
     */
    protected function seek(int $line) : self
    {
        $this->_file->seek($line);

        return $this;
    }


    /**
     * @param  int  $index
     *
     * @return $this
     */
    protected function seekFrame(int $index) : self
    {
        $line = $index + $this->header()->size();

        if ($line < $this->header()->size()) {
            $line = $this->header()->size();
        }

        if ($line > $this->totalLines()) {
            $line = $this->totalLines();
        }

        return $this->seek($line - 1);
    }


    /**
     * @param  int  $count
     *
     * @return self
     */
    protected function seekEndFrames(int $count = 1) : self
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
