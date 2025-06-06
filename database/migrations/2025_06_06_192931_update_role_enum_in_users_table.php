<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('ADMIN', 'SDM', 'PEGAWAI') DEFAULT 'PEGAWAI'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('SDM', 'PEGAWAI') DEFAULT 'PEGAWAI'");
    }
};
