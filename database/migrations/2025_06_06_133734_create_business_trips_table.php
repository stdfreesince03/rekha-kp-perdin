<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_trips', function (Blueprint $table) {
            $table->id();
            $table->string('trip_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('purpose');
            $table->date('departure_date');
            $table->date('return_date');
            $table->foreignId('origin_city_id')->constrained('cities');
            $table->foreignId('destination_city_id')->constrained('cities');
            $table->integer('duration_days');
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('daily_allowance', 10, 2)->nullable();
            $table->decimal('total_allowance', 12, 2)->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index(['departure_date', 'return_date']);
            $table->index('trip_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_trips');
    }
};
