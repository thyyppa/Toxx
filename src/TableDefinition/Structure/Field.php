<?php namespace Hyyppa\Toxx\TableDefinition\Structure;

use Hyyppa\Toxx\Contracts\TDF\FieldInterface;
use Hyyppa\Toxx\Exceptions\TdfException;
use Hyyppa\Toxx\Traits\LazyAccessor;
use Hyyppa\Toxx\Utils\BinaryRead;

/**
 * TDF Field Structure
 * [
 *      byte:       bit 1 sets read-only, the next 7 define data type [ @see Type::TYPES ]
 *      string:     name
 *      string:     alias
 *      string:     processing method (average, totalize, etc)
 *      string:     unit suffix
 *      string:     description
 *      uint4:      dimension start index [?]
 *      uint4:      dimension array size
 *      uint4[]:    dimension item sizes...
 *      byte:       [null terminator]
 * ]
 */
class Field implements FieldInterface
{

    use LazyAccessor;

    /**
     * Field name
     *
     * @var string
     */
    protected $_name;

    /**
     * Field alias
     *
     * @var string
     */
    protected $_alias;

    /**
     * Field Type
     *
     * @see Type::TYPES
     *
     * @var int
     */
    protected $_type;

    /**
     * Field Is Read-Only
     *
     * @var int
     */
    protected $_read_only;

    /**
     * Output Processing Instruction type
     *
     * For example, if you use Sample(1,variable_name,FP2) this will be "Smp"
     * if you use Totalize(1,variable_name,IEEE4,False) it will be "Tot"
     * See the loggernet manual under Appendix B, Table B-1 "Output Instruction Suffixes"
     * for more.
     *
     * @see https://s.campbellsci.com/documents/us/manuals/loggernet.pdf#page=478
     *
     * @var string
     */
    protected $_processing;

    /**
     * Unit Suffix
     *
     * Use in CRBASIC after defining your variables like so:
     *
     * CRBASIC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     *  Public humidity
     *  Units humidity=%
     *
     *  ~~~~~ or ~~~~~
     *
     *  Public windspeed   : Units windspeed=mph
     *  Public temperature : Units temperature=℉
     *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     * @var string
     */
    protected $_unit;

    /**
     * Field Description
     *
     * Use in CRBASIC by following your Sample(), Max(), etc in the
     * DataTable like so:
     *
     * CRBASIC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     *  DataTable (TableName,True,-1)
     *      Sample(1,name_of_field_variable,FP2)
     *      FieldNames('AliasForFieldAbove:A description of the field.')
     *  EndTable
     *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     *
     * If no description is explicitly set this will usually be set
     * to the processing method.
     *
     * @var string
     */
    protected $_description;

    /**
     * @var int
     */
    protected $_start_index;

    /**
     * Length of dimension block in bits.
     * This is typically used for arrays and strings
     *
     * @var int
     */
    protected $_dimension_size;

    /**
     * Array storing the size of dimension items.
     * Sizes are measured in bits as 4 byte unsigned integers.
     *
     * @var array|int
     */
    protected $_dimensions = [];


    /**
     * @var bool
     */
    protected $_is_last_field;


    /**
     * @param $file resource
     */
    public function __construct(&$file)
    {
        $type                  = new Type(BinaryRead::byte($file));
        $this->_type           = $type->name();
        $this->_read_only      = $type->isReadOnly();
        $this->_name           = BinaryRead::string($file);
        $this->_alias          = BinaryRead::string($file);
        $this->_processing     = BinaryRead::string($file);
        $this->_unit           = BinaryRead::string($file);
        $this->_description    = BinaryRead::string($file);
        $this->_start_index    = BinaryRead::uint4($file);
        $this->_dimension_size = BinaryRead::uint4($file);
        $this->_dimensions     = BinaryRead::array($file);
        $this->_is_last_field  = BinaryRead::peekNext($file) === "\x00";
    }


    /**
     * @return string
     */
    public function name() : string
    {
        return $this->_name;
    }


    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'name'       => $this->_name,
            'type'       => $this->_type,
            'processing' => $this->_processing,
            'unit'       => $this->_unit,
        ];
    }


    /**
     * True if this is the last field in the parent table
     *
     * @return bool
     */
    public function isLastField() : bool
    {
        return $this->_is_last_field;
    }


    /**
     * @param  string  $prop
     *
     * @return mixed|null
     */
    public function get(string $prop)
    {
        return $this->$prop;
    }


    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        throw new TdfException('TDF data is read only.');
    }

}
