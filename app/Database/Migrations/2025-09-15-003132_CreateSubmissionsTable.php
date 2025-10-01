<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubmissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'lecturer_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'course_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'assessment_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'group_members' => ['type' => 'TEXT', 'null' => true],
            'filename'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'uploaded_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        // Foreign keys (✅ fixed "course_assessments" → "assessments")
        $this->forge->addForeignKey('lecturer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assessment_id', 'assessments', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('submissions');
    }

    public function down()
    {
        $this->forge->dropTable('submissions');
    }
}
