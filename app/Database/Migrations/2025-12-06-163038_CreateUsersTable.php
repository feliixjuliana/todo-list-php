<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_login' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'user_password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('user_id', true);
        $this->forge->addUniqueKey('user_login');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('users', true);
        $this->db->enableForeignKeyChecks();
    }
}
