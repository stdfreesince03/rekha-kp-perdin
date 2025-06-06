<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['PEGAWAI', 'SDM'])->default('PEGAWAI')->after('email');
            $table->string('employee_id')->nullable()->after('role');
            $table->string('department')->nullable()->after('employee_id');
            $table->boolean('is_active')->default(true)->after('department');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'employee_id', 'department', 'is_active']);
        });
    }
};
