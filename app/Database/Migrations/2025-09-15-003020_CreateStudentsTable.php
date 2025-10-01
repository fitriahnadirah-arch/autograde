<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'student_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'course_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'class_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // 🔥 new
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE'); // 🔥 new
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}
