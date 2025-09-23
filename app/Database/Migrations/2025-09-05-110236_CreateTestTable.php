<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'    => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('test_user');
    }

    public function down()
    {
        $this->forge->dropTable('test_user');
    }
}
