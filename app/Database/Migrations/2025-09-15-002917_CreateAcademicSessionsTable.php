<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAcademicSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'session_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'status' => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'inactive'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('academic_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('academic_sessions');
    }
}
