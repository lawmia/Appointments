<?php


declare(strict_types=1);

namespace OCA\Appointments\Migration;

use OCA\Appointments\Backend\BackendUtils;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version010201Date20210919000001 extends SimpleMigrationStep
{

    /**
     * @param IOutput $output
     * @param \Closure $schemaClosure
     * @param array $options
     * @return ISchemaWrapper|null
     */
    public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options)
    {

        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if ($schema->hasTable(BackendUtils::HASH_TABLE_NAME)) {
            $table = $schema->getTable(BackendUtils::HASH_TABLE_NAME);

            if(!$table->hasColumn("user_id")){
                $table->addColumn('user_id', 'string', [
                    'notnull' => false,
                    'length' => 64
                ]);
            }

            if(!$table->hasColumn("start")){
                $table->addColumn('start', 'bigint', [
                    'notnull' => false,
                    'length' => 11,
                    'unsigned' => true,
                ]);
            }

            $user_index_name=BackendUtils::HASH_TABLE_NAME . '_user_index';
            if(!$table->hasIndex($user_index_name)){
                $table->addIndex(['user_id'],$user_index_name);
            }
            $start_index_name=BackendUtils::HASH_TABLE_NAME . '_start_index';
            if(!$table->hasIndex($start_index_name)){
                $table->addIndex(['start'],$start_index_name);
            }
        }

        return $schema;
    }

}
