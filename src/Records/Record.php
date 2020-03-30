<?php namespace Hyyppa\Toxx\Records;

use Carbon\Carbon;
use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Reading\ReadingCollectionInterface;
use Hyyppa\Toxx\Contracts\Reading\ReadingInterface;
use Hyyppa\Toxx\Contracts\Record\JsonableRecordInterface;
use Hyyppa\Toxx\Contracts\Record\RecordInterface;
use Hyyppa\Toxx\Traits\HasSettings;
use Hyyppa\Toxx\Traits\JsonableRecord;
use Hyyppa\Toxx\Traits\LazyAccessor;
use JsonSerializable;
use const STR_PAD_LEFT;

class Record implements RecordInterface, JsonSerializable, JsonableRecordInterface
{

    use HasSettings, LazyAccessor, JsonableRecord;

    /**
     * @var ReadingCollectionInterface
     */
    protected $_readings;

    /**
     * @var FileHeaderInterface
     */
    protected $_header;

    /**
     * @var string
     */
    protected $_binary;


    /**
     * @param  ReadingCollectionInterface  $readings
     * @param  FileHeaderInterface         $header
     * @param  int|null                    $record_number
     */
    public function __construct(
        ReadingCollectionInterface $readings,
        FileHeaderInterface &$header,
        int $record_number = null
    ) {
        $this->_readings = $readings;
        $this->_header   = $header;
        $this->_settings = &$header->settings;

        $this->setTimestamp()
             ->setSeconds()
             ->setRecordNumber($record_number);
    }


    /**
     * @return ReadingCollectionInterface|ReadingInterface
     */
    public function readings() : ReadingCollectionInterface
    {
        return $this->_readings;
    }


    /**
     * @param $fields
     *
     * @return array
     */
    public function only($fields) : array
    {
        if ( ! is_array($fields)) {
            $fields = [$fields];
        }

        return $this->readings()->filter(
            function (ReadingInterface $reading) use ($fields) {
                return $this->inArray($reading->field(), $fields);
            }
        )->mapWithKeys(
            static function (ReadingInterface $reading) {
                return [$reading->name() => $reading->value()];
            }
        )->toArray();
    }


    /**
     * @return array
     */
    public function simple() : array
    {
        return $this
            ->readings()
            ->reject(static function (ReadingInterface $reading) {
                return $reading->isHidden();
            })
            ->mapWithKeys(
                static function (ReadingInterface $reading) {
                    $value = $reading->value();

                    return [$reading->field() => $value];
                }
            )
            ->toArray();
    }


    /**
     * @return array
     */
    public function array() : array
    {
        return $this->simple();
    }


    /**
     * @return array
     */
    public function human() : array
    {
        return $this->withUnits()->toArray();
    }


    /**
     * @return array
     */
    public function arrayWithUnits() : array
    {
        return $this->human();
    }


    /**
     * @return ReadingCollectionInterface
     */
    public function withUnits() : ReadingCollectionInterface
    {
        return $this
            ->readings()
            ->reject(static function (ReadingInterface $reading) {
                return $reading->isHidden();
            })
            ->mapWithKeys(
                function (ReadingInterface $reading) {

                    $value = $reading->value();
                    $unit  = $reading->unit();

                    if ($this->inArray($reading->field(), $this->settings()->disabledUnits())) {
                        $unit = null;
                    }

                    if ($unit) {
                        return [$reading->field() => $value.$unit];
                    }

                    return [$reading->field() => $value];
                }
            );
    }


    /**
     * @return array
     */
    public function fields() : array
    {
        return $this
            ->readings()
            ->mapWithKeys(
                static function (ReadingInterface $reading) {
                    $value = $reading->value();

                    return [$reading->field() => is_numeric($value) ? $value + 0 : $value];
                }
            )
            ->except($this->settings()->hidden())
            ->keys()
            ->toArray();
    }


    /**
     * @param  string  $field
     *
     * @return bool
     */
    public function hasReading(string $field) : bool
    {
        return $this->hasReadings([$field]);
    }


    /**
     * @param  array  $fields
     *
     * @return bool
     */
    public function hasReadings(array $fields = []) : bool
    {
        return $this->readings()->filter(
                function (ReadingInterface $reading) use ($fields) {
                    return $this->inArray($reading->originalField(), $fields);
                }
            )->count() >= count($fields);
    }


    /**
     * @param  string  $field
     *
     * @return ReadingInterface
     */
    public function getReading(string $field) : ReadingInterface
    {
        return $this->readings()->filter(
            static function (ReadingInterface $reading) use ($field) {
                return strtolower($reading->originalField()) === strtolower($field);
            }
        )->first();
    }


    /**
     * @param  string  $field
     *
     * @return mixed|null
     */
    public function getValue(string $field)
    {
        if ( ! $this->hasReading($field)) {
            return null;
        }

        return $this->getReading($field)->value();
    }


    /**
     * @param  string  $field
     *
     * @return mixed|null
     */
    public function getRawValue(string $field)
    {
        if ( ! $this->hasReading($field)) {
            return null;
        }

        return $this->getReading($field)->rawValue();
    }


    /**
     * @param  bool  $raw
     *
     * @return string|null
     */
    public function getTimestamp($raw = false) : ?string
    {
        $method = 'getValue';

        if ($raw) {
            $method = 'getRawValue';
        }

        if ($this->hasReading('TIMESTAMP')) {
            return $this->$method('TIMESTAMP');
        }

        if ($this->hasReading('TMSTAMP')) {
            return $this->$method('TMSTAMP');
        }

        return null;
    }


    /**
     * @param  bool  $raw
     *
     * @return int|string|null
     */
    public function getSeconds($raw = false)
    {
        $method = 'getValue';

        if ($raw) {
            $method = 'getRawValue';
        }

        if ($this->hasReading('SECONDS')) {
            return $this->$method('SECONDS');
        }

        if ($this->hasReading('TIMESTAMP')) {
            return $this->$method('TIMESTAMP');
        }

        if ($this->hasReading('TMSTAMP')) {
            return $this->$method('TMSTAMP');
        }

        return null;
    }


    /**
     * @param  bool  $raw
     *
     * @return int|null
     */
    public function getRecordNumber($raw = false) : ?int
    {
        $method = 'getValue';

        if ($raw) {
            $method = 'getRawValue';
        }

        if ($this->hasReading('RECORD')) {
            return $this->$method('RECORD');
        }

        if ($this->hasReading('RECNBR')) {
            return $this->$method('RECNBR');
        }

        return null;
    }


    /**
     * @return string
     */
    public function arrayKey() : string
    {
        $key = $this->settings()->key();

        if (is_callable($key)) {
            return $key($this);
        }

        switch ($key) {
            case('timestamp'):
                return $this->getTimestamp(true);
            case('record'):
                return $this->getRecordNumber(true);
            case('seconds'):
            default:
                return $this->getSeconds(true);
        }
    }


    /**
     * @param  string  $name
     *
     * @return mixed|ReadingInterface
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if (method_exists($this, $name)) {
            return $this->$name();
        }

        if ($this->hasReading($name)) {
            return $this->getValue($name);
        }

        return null;
    }


    /**
     *
     */
    protected function setTimestamp() : RecordInterface
    {
        if ($this->hasReading('TIMESTAMP')) {
            return $this;
        }

        if ($this->hasReading('TMSTAMP')) {

            $this->readings()->addReading(
                $this->getValue('TMSTAMP'),
                'TIMESTAMP'
            );

            $this->removeReadings('TMSTAMP');

            return $this;
        }

        if ($this->hasReadings(['_YEAR_', '_DAY_', '_TIME_', '_SECONDS_'])) {

            $this->readings()->addReading(
                $this->timestampFromYDTS(),
                'TIMESTAMP'
            );

            $this->removeReadings(['_year_', '_day_', '_time_', '_seconds_']);

            return $this;
        }

        if ($this->hasReading('SECONDS')) {

            $this->readings()->addReading(
                $this->secondsToTimestamp($this->getValue('SECONDS')),
                'TIMESTAMP'
            );

        }

        return $this;
    }


    /**
     * @return RecordInterface
     */
    public function setSeconds() : RecordInterface
    {
        if ($this->hasReading('SECONDS')) {
            return $this;
        }

        if ($this->hasReading('TIMESTAMP')) {

            $this->readings()->addReading(
                $this->timestampToSeconds($this->getValue('TIMESTAMP')),
                'SECONDS'
            );

        }

        return $this;
    }


    /**
     * @param  int|null  $number
     *
     * @return RecordInterface
     */
    public function setRecordNumber(int $number = null) : RecordInterface
    {
        if ($this->hasReading('RECORD')) {
            return $this;
        }

        if ($number !== null) {
            $this->readings()->addReading($number, 'RECORD');

            return $this;
        }

        if ($this->hasReading('RECNBR')) {

            $this->readings()->addReading(
                (int) $this->getValue('RECNBR'),
                'RECORD'
            );

            $this->removeReadings('recnbr');
        }

        return $this;
    }


    /**
     * @return string
     */
    protected function timestampFromYDTS() : string
    {
        $time = str_pad($this->getValue('_TIME_'), 4, '0', STR_PAD_LEFT);
        [$hours, $minutes] = str_split($time, 2);

        $date = Carbon::create($this->getValue('_YEAR_'))
                      ->addDays($this->getValue('_DAY_') - 1)
                      ->addHours($hours)
                      ->addMinutes($minutes)
                      ->addSeconds($this->getValue('_SECONDS_'));

        return $date->format('Y-m-d H:i:s');
    }


    /**
     * @param  string|Carbon  $timestamp
     *
     * @return int
     */
    protected function timestampToSeconds($timestamp) : int
    {
        if ( ! $timestamp instanceof Carbon) {
            $timestamp = Carbon::parse($timestamp);
        }

        return Carbon::parse('1990-01-01 00:00:00')->diffInSeconds($timestamp);
    }


    /**
     * @param $readings
     *
     * @return RecordInterface
     */
    public function removeReadings($readings) : RecordInterface
    {
        if ( ! is_array($readings)) {
            $readings = [$readings];
        }

        $unset = $this->readings()->filter(
            function (ReadingInterface $reading) use ($readings) {
                return $this->inArray($reading->field(), $readings);
            }
        )->keys();

        foreach ($unset as $k => $v) {
            unset($this->_readings[ $v ]);
        }

        return $this;
    }


    /**
     * @param  string  $needle
     * @param  array   $haystack
     *
     * @return bool
     */
    public function inArray(string $needle, array $haystack) : bool
    {
        return in_array(
            strtolower($needle),
            array_map('strtolower', $haystack),
            true
        );
    }


    /**
     * @param  int  $seconds
     *
     * @return string
     */
    protected function secondsToTimestamp(int $seconds) : string
    {
        return Carbon::parse('1990-01-01 00:00:00')
                     ->addSeconds($seconds)
                     ->format('Y-m-d H:i:s');
    }


    /**
     * @return RecordInterface
     */
    public function withHidden() : RecordInterface
    {
        $this->settings()->hidden([]);

        return $this;
    }


    /**
     * @param  string  $binary
     *
     * @return RecordInterface
     */
    public function setBinary(string $binary) : RecordInterface
    {
        $this->_binary = $binary;

        return $this;
    }

}
