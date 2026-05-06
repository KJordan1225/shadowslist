<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');
            $table->text('description');

            $table->string('city');
            $table->string('state', 2);
            $table->string('zip', 10);

            $table->date('desired_date')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();

            $table->enum('status', [
                'open',
                'closed',
                'cancelled',
            ])->default('open');

            $table->timestamps();

            $table->index(['status', 'category_id']);
            $table->index(['city', 'state', 'zip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
