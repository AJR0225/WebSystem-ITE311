<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSemesterAndAcademicYearToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'semester' => [
                'type' => 'ENUM',
                'constraint' => ['1st Semester', '2nd Semester'],
                'null' => true,
                'after' => 'status',
            ],
            'academic_year' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'semester',
            ],
        ];
        
        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['semester', 'academic_year']);
    }
}
