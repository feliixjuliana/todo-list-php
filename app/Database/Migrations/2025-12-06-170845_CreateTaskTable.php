<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'task_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],

            'user_id' => [
                'type'      => 'INT',
                'unsigned'  => true,
                'null'      => false,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],

            'start_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],

            'end_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],

            'status' => [
                'type'    => "ENUM('pendente','completado','cancelado')",
                'default' => 'pendente',
                'null'    => false
            ],
        ]);

        $this->forge->addKey('task_id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('tasks', true);
        $this->db->table('tasks')->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('tasks', true);
        $this->db->enableForeignKeyChecks();
    }
}
