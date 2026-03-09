<?php

namespace App\EventListener\Doctrine;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class IgnoreTablesListener
{
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        $dbName = $args->getEntityManager()->getConnection()->getDatabase();

        foreach ($schema->getTableNames() as $tableName) {
            if (strpos($tableName, $dbName . '.v_') === 0) {
                $schema->dropTable($tableName);
            }
        }
    }
}
