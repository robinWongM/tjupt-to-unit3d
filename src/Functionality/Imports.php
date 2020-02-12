<?php

namespace robinWongM\TjuptToUnit3d\Functionality;

use ErrorException;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;

class Imports
{
    /**
     * @param  Connection  $database
     * @param  string  $type
     * @param  string  $oldTable
     * @param  string  $modelName
     * @param  string  $autoTimestamp
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function importTable(Connection $database, string $type, string $oldTable, string $modelName, string $autoTimestamp): int
    {
        $results = 0;

        if (! $database->getSchemaBuilder()->hasTable($oldTable)) {
            throw new ErrorException('`'.$oldTable.'` table missing.');
        }

        $oldData = $database->query()->select()->from($oldTable)->get();

        foreach ($oldData->all() as $oldDataItem) {
            $data = Mapping::map($type, $oldDataItem);

            if (self::import($modelName, $data, $autoTimestamp)) {
                $results++;
            }
        }

        return $results;
    }

    /**
     * @param  string  $model
     * @param  array  $data
     * @param  bool   $updateTimestamp
     * @return bool
     */
    private static function import(string $model, array $data = [], bool $updateTimestamp): bool
    {
        /** @var Model $new */
        $new = new $model();

        foreach ($data as $item => $value) {
            $new->$item = $value;
        }

        $new->timestamps = $updateTimestamp;
        return $new->save();
    }
}
