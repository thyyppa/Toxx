<?php namespace Hyyppa\Toxx\TableDefinition;

use Hyyppa\Toxx\Contracts\TDF\TdfInterface;
use Hyyppa\Toxx\Exceptions\TdfException;
use Hyyppa\Toxx\TableDefinition\Structure\Table;
use Hyyppa\Toxx\TableDefinition\Structure\Tables;
use Hyyppa\Toxx\Traits\LazyAccessor;
use Hyyppa\Toxx\Utils\BinaryRead;
use Hyyppa\Toxx\Utils\FileSystem;

/**
 * TDF Structure
 * [
 *      byte:       version [?]
 *      Table[]:    data tables... [ @see Table ]
 *      [eof]
 * ]
 */
class TDF implements TdfInterface
{

    use LazyAccessor;

    /**
     * @var int
     */
    protected $_size;

    /**
     * @var int
     */
    protected $_version;

    /**
     * @var Tables
     */
    protected $_tables;

    /**
     * @var resource
     */
    private $__file;


    /**
     * @param  string  $path
     */
    public function __construct(string $path)
    {
        FileSystem::AssertNotEmpty($path, 'TDF');

        $this->__file = fopen($path, 'rb');

        $this->setSize()
             ->setVersion()
             ->readTables()
             ->closeFile();
    }


    /**
     * @param  string  $filename
     *
     * @return TdfInterface
     */
    public static function load(string $filename) : TdfInterface
    {
        return new static($filename);
    }


    /**
     *
     * @return self
     */
    private function setSize() : self
    {
        $this->_size = BinaryRead::size($this->__file);

        return $this;
    }


    /**
     *
     * @return self
     */
    private function setVersion() : self
    {
        $this->_version = mb_ord(BinaryRead::peekOffset($this->__file, 0));

        return $this;
    }


    /**
     *
     * @return self
     */
    protected function readTables() : self
    {
        while ($this->hasData()) {
            if ($this->nextTable()->isLastTable()) {
                break;
            }
        }

        return $this;
    }


    /**
     * @return Table
     */
    protected function nextTable() : Table
    {
        return $this->addTable(new Table($this->__file));
    }


    /**
     * @param  Table  $table
     *
     * @return Table
     */
    protected function addTable(Table $table) : Table
    {
        $this->all()->push($table);

        return $table;
    }


    /**
     * @return Tables
     */
    public function all() : Tables
    {
        if ( ! $this->_tables) {
            $this->_tables = new Tables();
        }

        return $this->_tables;
    }


    /**
     * @param  string  $name
     *
     * @return Table|null
     */
    public function get(string $name) : ?Table
    {
        if ( ! $this->has($name)) {
            throw new TdfException('The table `'.$name.'` does not exist!');
        }

        return $this->_tables->filter(
            static function (Table $t) use ($name) {
                return $t->name() === $name;
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
        return $this->_tables->filter(
                static function (Table $t) use ($name) {
                    return $t->name() === $name;
                }
            )->first() !== null;
    }


    /**
     *
     * @return bool
     */
    protected function hasData() : bool
    {
        return ! feof($this->__file);
    }


    /**
     * @return self
     */
    protected function closeFile() : self
    {
        fclose($this->__file);

        return $this;
    }


    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }


    /**
     * @param $param
     *
     * @return Table|Tables|mixed|null
     */
    public function __get($param)
    {
        switch (true) {
            case($this->canBeLazy($param)):
                return $this->shouldBeLazy($param);
            case($this->has($param)):
                return $this->get($param);
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

