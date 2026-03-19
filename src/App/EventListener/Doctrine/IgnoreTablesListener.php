<?php

namespace App\EventListener\Doctrine;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class IgnoreTablesListener
{
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        foreach ($schema->getTables() as $table) {
            $tableName = $table->getName();
            if (strpos($tableName, 'v_') === 0) {
                $schema->dropTable($tableName);
            }
        }
    }
}
