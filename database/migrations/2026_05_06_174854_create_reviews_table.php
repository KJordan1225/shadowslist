<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->unsignedTinyInteger('quality_rating')->nullable();
            $table->unsignedTinyInteger('responsiveness_rating')->nullable();
            $table->unsignedTinyInteger('punctuality_rating')->nullable();
            $table->unsignedTinyInteger('professionalism_rating')->nullable();

            $table->string('title')->nullable();
            $table->text('body');

            $table->string('service_used')->nullable();
            $table->date('service_date')->nullable();
            $table->decimal('price_paid', 10, 2)->nullable();

            $table->boolean('would_hire_again')->default(true);

            $table->text('owner_response')->nullable();
            $table->timestamp('owner_responded_at')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
