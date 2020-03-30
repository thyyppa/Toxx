<?php namespace Hyyppa\Toxx\TableDefinition\Structure;

use Carbon\Carbon;
use Hyyppa\Toxx\Contracts\TDF\FieldInterface;
use Hyyppa\Toxx\Contracts\TDF\TableInterface;
use Hyyppa\Toxx\Exceptions\TdfException;
use Hyyppa\Toxx\Traits\LazyAccessor;
use Hyyppa\Toxx\Utils\BinaryRead;

/**
 * TDF Table Structure
 * [
 *      byte:       null, skip it
 *      string:     name
 *      uint4:      size
 *      byte:       time type           [ @see Type::TYPES ]
 *      nsec:       8-byte time         [ @see BinaryRead::NSec() ]
 *      nsec:       8-byte interval     [ @see BinaryRead::NSec() ]
 *      Field[]:    table fields...     [ @see FieldInterface ]
 *      byte:       [null terminator]
 * ]
 */
class Table implements TableInterface
{

    use LazyAccessor;

    /**
     * Table Name
     *
     * @var string
     */
    protected $_name;

    /**
     * Table size in
     *
     * @var int
     */
    protected $_size;

    /**
     * @var int
     */
    protected $_time_type;

    /**
     * @var Carbon
     */
    protected $_time;

    /**
     * @var Carbon
     */
    protected $_interval;

    /**
     * @var Fields
     */
    protected $_fields;

    /**
     * @var bool
     */
    protected $_is_last_table;

    /**
     * @var resource
     */
    private $__file;


    /**
     * @param  resource  $file
     */
    public function __construct(&$file)
    {
        $this->__file = $file;

        $this->skipFirstByte()
             ->setName()
             ->setSize()
             ->setTime()
             ->readFields($file)
             ->checkIfLastTable();
    }


    /**
     * @return Fields
     */
    public function fields() : Fields
    {
        if ( ! $this->_fields) {
            $this->_fields = new Fields();
        }

        return $this->_fields;
    }


    /**
     * @param  string  $name
     *
     * @return Field|null
     */
    public function field(string $name) : ?FieldInterface
    {
        return $this->fields()->filter(
            static function (Field $f) use ($name) {
                return $f->name() === $name;
            }
        )->first();
    }


    /**
     * @param  string  $name
     *
     * @return bool
     */
    public function has(string $name) : bool
    {
        return $this->field($name) !== null;
    }


    /**
     * @return bool
     */
    public function isLastTable() : bool
    {
        return $this->_is_last_table;
    }


    /**
     * @return string
     */
    public function name() : string
    {
        return $this->_name;
    }


    /**
     * @return int
     */
    public function interval() : int
    {
        return $this->_interval;
    }


    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'name'   => $this->name(),
            'fields' => $this->fields()->toArray(),
        ];
    }


    /**
     * @return array
     */
    public function fieldNames() : array
    {
        return $this->fields()->map(function (Field $f) {
            return $f->name();
        })->all();
    }


    /**
     * @return $this
     */
    protected function readFields() : Table
    {
        while ($this->hasData()) {
            if ($this->nextField()->isLastField()) {
                break;
            }
        }

        return $this;
    }


    /**
     * @return Field
     */
    protected function nextField() : Field
    {
        return $this->addField(new Field($this->__file));
    }


    /**
     * @param  Field  $field
     *
     * @return Field
     */
    protected function addField(Field $field) : Field
    {
        $this->fields()->push($field);

        return $field;
    }


    /**
     * @return $this
     */
    protected function setName() : Table
    {
        $this->_name = BinaryRead::string($this->__file);

        return $this;
    }


    /**
     * @return $this
     */
    protected function setSize() : Table
    {
        $this->_size = BinaryRead::uint4($this->__file);

        return $this;
    }


    /**
     * @return $this
     */
    protected function setTime() : Table
    {
        $this->_time_type = (new Type(BinaryRead::byte($this->__file)))->name();
        $this->_time      = BinaryRead::NSec($this->__file)->toString();
        $this->_interval  = BinaryRead::NSec($this->__file, false);

        return $this;
    }


    /**
     * @return $this
     */
    protected function skipFirstByte() : Table
    {
        BinaryRead::skip($this->__file);

        return $this;
    }


    /**
     * @return $this
     */
    protected function checkIfLastTable() : Table
    {
        $this->_is_last_table = BinaryRead::peekNext($this->__file, 2) === "\x00";

        return $this;
    }


    /**
     * @return bool
     */
    protected function hasData() : bool
    {
        return ! feof($this->__file);
    }


    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name) : bool
    {
        return $this->has($name);
    }


    /**
     * @param $param
     *
     * @return Field|Fields|mixed|null
     */
    public function __get($param)
    {
        switch (true) {
            case($this->canBeLazy($param)):
                return $this->shouldBeLazy($param);
            case($this->has($param)):
                return $this->field($param);
            default:
                return null;
        }
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
