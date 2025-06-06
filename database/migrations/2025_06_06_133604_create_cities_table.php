<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('province');
            $table->string('island');
            $table->boolean('is_foreign')->default(false);
            $table->string('country')->default('Indonesia');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['province', 'island']);
            $table->index('is_foreign');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
