<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestCasesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'assessment_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'input'          => ['type' => 'TEXT', 'null' => false],
            'expected_output'=> ['type' => 'TEXT', 'null' => false],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        // ✅ Fixed foreign key target
        $this->forge->addForeignKey('assessment_id', 'assessments', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('test_cases');
    }

    public function down()
    {
        $this->forge->dropTable('test_cases');
    }
}
