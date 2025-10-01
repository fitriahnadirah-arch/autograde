<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'course_code'   => ['type' => 'VARCHAR', 'constraint' => 20],
            'course_name'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'semester'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'credit_hour'   => ['type' => 'INT', 'constraint' => 2],
            'coordinator_id'=> ['type' => 'INT', 'unsigned' => true],
            'session_id'    => ['type' => 'INT', 'unsigned' => true], // ✅ foreign key
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('coordinator_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'academic_sessions', 'id', 'CASCADE', 'CASCADE'); // ✅
        $this->forge->createTable('admin_courses');
    }

    public function down()
    {
        $this->forge->dropTable('admin_courses');
    }
}
