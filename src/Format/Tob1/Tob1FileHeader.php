<?php namespace Hyyppa\Toxx\Format\Tob1;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Exceptions\UndefinedTypeException;
use Hyyppa\Toxx\Exceptions\UndefinedUnitException;
use Hyyppa\Toxx\Format\General\FileHeader;
use const FILTER_SANITIZE_NUMBER_INT;

class Tob1FileHeader extends FileHeader
{

    /**
     *
     */
    protected const sizes = [
        'BOOL'    => 1, // 8 bits
        'FP2'     => 2, // 16 bits
        'USHORT'  => 2, // 16 bits
        'LONG'    => 4, // 32 bits
        'ULONG'   => 4, // 32 bits
        'IEEE4'   => 4, // 32 bits
        'IEEE8'   => 8, // 64 bits
        'SecNano' => 8, // 64 bits
    ];


    /**
     * @param  array  $info
     *
     * @return FileHeaderInterface
     */
    public function setInfo(array $info) : FileHeaderInterface
    {
        return parent::setInfo(
            array_combine([
                'format',
                'station',
                'datalogger',
                'serial_number',
                'os_version',
                'dld_name',
                'dld_signature',
                'table',
            ], $info)
        );
    }


    /**
     * @param  null  $offset
     *
     * @return mixed
     */
    public function types($offset = null)
    {
        if ( ! $this->_types) {
            throw new UndefinedTypeException('No types defined.');
        }

        if ($offset !== null) {
            if ( ! isset($this->_types[ $offset ])) {
                throw new UndefinedTypeException('No types defined at frame offset '.$offset);
            }

            return $this->_types[ $offset ];
        }

        return $this->_types;
    }


    /**
     * @param  null  $offset
     *
     * @return int
     */
    public function frameSize($offset = null) : int
    {
        if ($offset !== null) {
            return $this->offsetSize($offset);
        }

        $size = 0;

        foreach ($this->types() as $_offset => $unit) {
            $size += $this->offsetSize($_offset);
        }

        return $size;
    }


    /**
     * @param  int  $offset
     *
     * @return int
     * @throws UndefinedUnitException
     */
    protected function offsetSize(int $offset) : int
    {
        $unit = $this->types($offset);

        if (stristr($unit, 'ASCII')) {
            return (int) filter_var($unit, FILTER_SANITIZE_NUMBER_INT);
        }

        if ( ! isset(self::sizes[ $unit ])) {
            throw new UndefinedUnitException("Unknown unit size: $unit");
        }

        return self::sizes[ $unit ];
    }

}
