<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnrollmentApprovalFields extends Migration
{
    public function up()
    {
        // First, modify the status ENUM to include pending, approved, declined
        // Note: MySQL doesn't support direct ENUM modification, so we'll use ALTER TABLE
        $this->db->query("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending', 'approved', 'declined', 'enrolled', 'completed', 'dropped') DEFAULT 'pending'");
        
        // Add decline_reason field
        $fields = [
            'decline_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status',
            ],
        ];
        
        $this->forge->addColumn('enrollments', $fields);
    }

    public function down()
    {
        // Remove decline_reason column
        $this->forge->dropColumn('enrollments', ['decline_reason']);
        
        // Revert status ENUM (optional - may cause issues if data exists)
        // $this->db->query("ALTER TABLE enrollments MODIFY COLUMN status ENUM('enrolled', 'completed', 'dropped') DEFAULT 'enrolled'");
    }
}
