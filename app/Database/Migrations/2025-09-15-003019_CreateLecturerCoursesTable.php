<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLecturerCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'lecturer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'course_id'   => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('lecturer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'admin_courses', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('lecturer_courses', true);
    }

    public function down()
    {
        $this->forge->dropTable('lecturer_courses', true);
    }
}
