<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('courses')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'course_id' => [ // FK ke admin_courses
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'session_id' => [ // FK ke academic_sessions
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);

            // Foreign keys
            $this->forge->addForeignKey('course_id', 'admin_courses', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE');

            $this->forge->createTable('courses');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('courses')) {
            $this->forge->dropTable('courses');
        }
    }
}
