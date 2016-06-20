<?php

use yii\db\Migration;

/**
 * Handles the creation for table `live_content`.
 */
class m160613_195202_create_live_content extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%live_content}}', [
            'id' => $this->primaryKey(),
            'place' => $this->string(255)->comment('ID place'),
            'type' => $this->string(255)->comment('Type of place'),
            'content' => $this->text()->comment('Content data'),
            'modified_at' => $this->dateTime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%live_content}}');
    }
}
