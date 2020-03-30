<?php namespace Hyyppa\Toxx\Format\Tob1;

use Hyyppa\Toxx\Contracts\DataFileInterface;
use Hyyppa\Toxx\Contracts\Record\RecordCollectionInterface;
use Hyyppa\Toxx\Contracts\SettingsInterface;
use Hyyppa\Toxx\Format\General\BaseDataFile;
use Hyyppa\Toxx\Records\Record;
use Hyyppa\Toxx\Records\Records;
use const SEEK_END;

class Tob1 extends BaseDataFile
{


    /**
     * @param  string             $filename
     * @param  SettingsInterface  $settings
     */
    public function __construct(string $filename, SettingsInterface $settings)
    {
        $this->_file = fopen($filename, 'rb');

        $this->_header = $this->parseHeader($this->_file, $settings);
    }


    /**
     * @param                     $file
     * @param  SettingsInterface  $settings
     *
     * @return Tob1FileHeader
     */
    protected function parseHeader($file, SettingsInterface $settings) : Tob1FileHeader
    {
        $header = new Tob1FileHeader($settings);

        $header->setInfo(
            $this->csvToArray(fgets($file))
        );
        $header->setFields(
            $this->csvToArray(fgets($file))
        );
        $header->setUnits(
            $this->csvToArray(fgets($file))
        );
        $header->setProcessing(
            $this->csvToArray(fgets($file))
        );
        $header->setTypes(
            $this->csvToArray(fgets($file))
        );

        $header->setSize(ftell($file));

        return $header;
    }


    /**
     * @return int
     */
    public function count() : int
    {
        if ($this->_count !== null) {
            return $this->_count;
        }

        $old_ptr = ftell($this->_file);

        fseek($this->_file, 0, SEEK_END);
        $length = ftell($this->_file);
        $size   = $length - $this->header()->size();

        fseek($this->_file, $old_ptr);

        return $this->_count = floor($size / $this->header()->frameSize());
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface|Record
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
     * @return RecordCollectionInterface|Record
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
     * @return RecordCollectionInterface|Record
     */
    public function next(int $count = 1)
    {
        if ($count === 1) {
            return $this->read()->first();
        }

        $end_frame = $this->currentFrame() + $count;
        if ($end_frame > $this->count()) {
            $this->seekFrame($this->count() - $count);
        }

        return $this->read($count);
    }


    /**
     * @param  int  $count
     *
     * @return RecordCollectionInterface|Record
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
     * @param  int  $count
     *
     * @return RecordCollectionInterface
     */
    public function read(int $count = 1) : RecordCollectionInterface
    {
        if ($this->currentFrame() + $count > $this->frameCount()) {
            $count = $this->frameCount() - $count;
        }

        $data = fread(
            $this->_file,
            $this->header()->frameSize() * $count
        );

        $data = str_split($data, $this->header()->frameSize());

        $records = Records::withHeader($this->_header);

        foreach ($data as $frame) {
            $records->addRecord(
                new Tob1Frame($frame, $this->_header)
            );
        }

        return $records;
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
     * @param  int  $per_page
     *
     * @return int
     */
    public function pageCount(int $per_page = 60) : int
    {
        return ceil($this->frameCount() / $per_page);
    }


    /**
     * @return RecordCollectionInterface
     */
    public function all() : RecordCollectionInterface
    {
        return $this->first($this->count());
    }


    /**
     * @return int
     */
    public function size() : int
    {
        $this->seekEnd();

        return ftell($this->_file);
    }


    /**
     * @return int
     */
    protected function frameCount() : int
    {
        $old_ptr = ftell($this->_file);

        $this->seekStart();
        $start = ftell($this->_file);

        $this->seekEnd();
        $end = ftell($this->_file);

        $this->seek($old_ptr);

        return floor(($end - $start) / $this->header()->frameSize());
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
     * @return int
     */
    protected function currentFrame() : int
    {
        $bytes = ftell($this->_file);
        $bytes -= $this->header()->size();
        $bytes /= $this->header()->frameSize();

        return $bytes - 1;
    }


    /**
     * @param  int  $frame_number
     *
     * @return DataFileInterface
     */
    protected function seekFrame(int $frame_number) : DataFileInterface
    {
        fseek(
            $this->_file,
            $this->header()->size() + ($frame_number * $this->header()->frameSize())
        );

        return $this;
    }


    /**
     * @return int
     */
    protected function pointerBytePosition() : int
    {
        return ftell($this->_file);
    }


    /**
     * @return int
     */
    protected function pointerFramePosition() : int
    {
        if ( ! $this->header()->frameSize()) {
            throw new \RuntimeException('Frame size undefined');
        }

        return (ftell($this->_file) - $this->header()->size()) / $this->header()->frameSize();
    }


    /**
     * @param  int  $bytes
     *
     * @return DataFileInterface
     */
    protected function seek(int $bytes) : DataFileInterface
    {
        fseek($this->_file, $bytes);

        return $this;
    }


    /**
     * @return DataFileInterface
     */
    protected function seekStart() : DataFileInterface
    {
        fseek(
            $this->_file,
            $this->header()->size()
        );

        return $this;
    }


    /**
     * @return DataFileInterface
     */
    protected function seekEnd() : DataFileInterface
    {
        fseek($this->_file, 0, SEEK_END);

        return $this;
    }

}
