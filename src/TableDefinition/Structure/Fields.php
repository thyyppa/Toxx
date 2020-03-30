<?php namespace Hyyppa\Toxx\TableDefinition\Structure;

use Hyyppa\Toxx\Contracts\TDF\FieldInterface;
use Hyyppa\Toxx\Utils\Collection;

/**
 * @method FieldInterface first()
 * @method FieldInterface last()
 * @method FieldInterface[] all()
 */
class Fields extends Collection
{

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->map(static function (Field $f) {
            return $f->toArray();
        })->all();
    }

}
