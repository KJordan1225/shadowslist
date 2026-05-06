<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount', 10, 2)->nullable();
            $table->text('message');
            $table->unsignedInteger('estimated_days')->nullable();

            $table->enum('status', [
                'sent',
                'accepted',
                'rejected',
                'withdrawn',
            ])->default('sent');

            $table->timestamps();

            $table->unique(['service_request_id', 'business_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
