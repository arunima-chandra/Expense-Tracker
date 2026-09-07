<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('important_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // bill, rent, emi, salary, other
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedTinyInteger('due_day')->nullable(); // 1-31 for recurring monthly
            $table->date('due_date')->nullable(); // specific one-time date
            $table->string('reminder_method')->default('notification'); // notification, alarm, both
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('important_dates');
    }
};
