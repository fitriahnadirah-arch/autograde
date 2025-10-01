<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRubricsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'assessment_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'criteria'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'weight'        => ['type' => 'FLOAT', 'null' => true],
            'scale_5'       => ['type' => 'TEXT', 'null' => true],
            'scale_4'       => ['type' => 'TEXT', 'null' => true],
            'scale_3'       => ['type' => 'TEXT', 'null' => true],
            'scale_2'       => ['type' => 'TEXT', 'null' => true],
            'scale_1'       => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        // ✅ Fixed: foreign key now points to "assessments" instead of "course_assessments"
        $this->forge->addForeignKey('assessment_id', 'assessments', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('rubrics');
    }

    public function down()
    {
        $this->forge->dropTable('rubrics');
    }
}
