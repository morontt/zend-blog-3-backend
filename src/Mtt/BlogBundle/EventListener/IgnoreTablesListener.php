<?php

namespace Mtt\BlogBundle\EventListener;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class IgnoreTablesListener
{
    /**
     * @var array
     */
    protected $ignoredTables = ['v_comments'];

    /**
     * @param GenerateSchemaEventArgs $args
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args)
    {
        $schema = $args->getSchema();

        $dbName = $args->getEntityManager()->getConnection()->getDatabase();
        $ignoredTables = array_map(function ($t) use ($dbName) {
            return $dbName . '.' . $t;
        }, $this->ignoredTables);

        $tableNames = $schema->getTableNames();
        foreach ($tableNames as $tableName) {
            if (in_array($tableName, $ignoredTables)) {
                $schema->dropTable($tableName);
            }
        }
    }
}
