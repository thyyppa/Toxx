<?php namespace Hyyppa\Toxx\TableDefinition\Structure;

use Hyyppa\Toxx\Utils\Collection;

class Tables extends Collection
{

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->mapWithKeys(static function (Table $table) {
            return [
                $table->name() => [
                    'fields'   => $table->fields()->count(),
                    'interval' => $table->interval(),
                ],
            ];
        })->all();
    }


    /**
     * @return array
     */
    public function toArrayWithFields() : array
    {
        return $this->mapWithKeys(static function (Table $table) {
            return [
                $table->name() => $table->fields()->toArray(),
            ];
        })->all();
    }

}
