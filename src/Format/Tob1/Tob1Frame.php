<?php namespace Hyyppa\Toxx\Format\Tob1;

use Hyyppa\Toxx\Contracts\Format\FileHeaderInterface;
use Hyyppa\Toxx\Contracts\Record\RecordInterface;
use Hyyppa\Toxx\Format\General\Frame;
use Hyyppa\Toxx\Records\Record;
use Hyyppa\Toxx\Utils\Unpack;

class Tob1Frame extends Frame
{

    protected $_binary;


    /**
     * @param  string               $data
     * @param  FileHeaderInterface  $header
     * @param  null                 $line_number
     */
    public function __construct(string $data, FileHeaderInterface &$header = null, $line_number = null)
    {
        $this->header      = $header;
        $this->line_number = $line_number;
        $this->_binary     = $data;
        $this->data        = $this->unpack($data);
    }


    /**
     * @param  string  $data
     *
     * @return array
     */
    protected function unpack(string $data) : array
    {
        $offset   = 0;
        $unpacked = [];

        foreach ($this->header->types() as $index => $type) {
            $value  = substr($data, $offset, $this->header->frameSize($index));
            $offset += $this->header->frameSize($index);
            $lsf    = stristr($type, '_LSF') ? true : null;

            switch (str_replace('_LSF', '', $type)) {
                case('FP2'):
                    $unpacked[ $index ] = Unpack::FP2($value);
                    break;
                case('FP3'):
                    $unpacked[ $index ] = Unpack::FP3($value);
                    break;
                case('FP4'):
                    $unpacked[ $index ] = Unpack::FP4($value);
                    break;
                case('IEEE4'):
                    $unpacked[ $index ] = Unpack::IEEE4($value, $lsf);
                    break;
                case('IEEE8'):
                    $unpacked[ $index ] = Unpack::IEEE8($value, $lsf);
                    break;
                case('UBYTE'):
                case('UINT1'):
                    $unpacked[ $index ] = Unpack::UInt1($value);
                    break;
                case('USHORT'):
                case('UINT2'):
                    $unpacked[ $index ] = Unpack::UInt2($value, $lsf);
                    break;
                case('ULONG'):
                case('UINT4'):
                    $unpacked[ $index ] = Unpack::UInt4($value, $lsf);
                    break;
                case('ULONG6'):
                case('UINT6'):
                    $unpacked[ $index ] = Unpack::UInt6($value, $lsf);
                    break;
                case('BYTE'):
                case('INT1'):
                    $unpacked[ $index ] = Unpack::Int1($value, $lsf);
                    break;
                case('SHORT'):
                case('INT2'):
                    $unpacked[ $index ] = Unpack::Int2($value, $lsf);
                    break;
                case('SecNano'):
                case('LONG'):
                case('INT4'):
                    $unpacked[ $index ] = Unpack::Int4($value, $lsf);
                    break;
                case('INT8'):
                    $unpacked[ $index ] = Unpack::Int8($value, $lsf);
                    break;
                case('BOOL'):
                    $unpacked[ $index ] = Unpack::Bool1($value);
                    break;
                case('BOOL2'):
                    $unpacked[ $index ] = Unpack::Bool2($value);
                    break;
                case('BOOL4'):
                    $unpacked[ $index ] = Unpack::Bool4($value);
                    break;
                case('BITS'):
                case('BOOL8'):
                    $unpacked[ $index ] = Unpack::Flags($value);
                    break;
                case('SEC'):
                case('SECONDS'):
                    $unpacked[ $index ] = Unpack::Seconds($value, $lsf);
                    break;
                case('USEC'):
                    $unpacked[ $index ] = Unpack::USec($value, $lsf);
                    break;
                case('NSEC'):
                    $unpacked[ $index ] = Unpack::NSec($value, $lsf);
                    break;
                case(stristr($type, 'BOOL')):
                    $unpacked[ $index ] = Unpack::Boolean($value);
                    break;
                case(stristr($type, 'ASCII')):
                    $unpacked[ $index ] = Unpack::Ascii($value);
                    break;
                case('CARBON'):
                    $unpacked[ $index ] = Unpack::Carbon($value);
                    break;
                default:
                    $data[ $index ] = 'Unknown type: '.$type;
            }

        }

        return $unpacked;
    }


    /**
     * @return RecordInterface
     */
    public function asRecord() : RecordInterface
    {
        $record = new Record($this->readings(), $this->header, $this->line_number);
        $record->setBinary(bin2hex($this->_binary));

        return $record;
    }

}
