<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToAllTables extends Migration
{
    public function up()
    {
        $tables = [
            'document_types',
            'institutions',
            'faculties',
            'program_studies',
            'roles',
            'users',
            'students',
            'documents',
            'failed_jobs',
            'migrations',
            'password_reset_tokens',
            'personal_access_tokens',
            'transactions',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes(); // This will add `deleted_at` TIMESTAMP NULL
            });
        }
    }

    public function down()
    {
        $tables = [
            'document_types',
            'institutions',
            'faculties',
            'program_studies',
            'roles',
            'users',
            'students',
            'documents',
            'failed_jobs',
            'migrations',
            'password_reset_tokens',
            'personal_access_tokens',
            'transactions',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
