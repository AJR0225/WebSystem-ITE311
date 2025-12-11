<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'schedule_days' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'academic_year',
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'schedule_days',
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'start_time',
            ],
        ];
        
        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['schedule_days', 'start_time', 'end_time']);
    }
}
