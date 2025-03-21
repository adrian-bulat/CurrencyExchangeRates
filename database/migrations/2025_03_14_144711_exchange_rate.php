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
        Schema::create('exchange_rate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('base_id');
            $table->unsignedBigInteger('target_id');
            $table->decimal('rate', 8, 2);
            $table->date('published_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->unique(['base_id', 'target_id','published_date'],);

            $table->foreign('base_id')
                ->references('id')
                ->on('currency_attribute')
                ->onDelete('cascade');

            $table->foreign('target_id')
                ->references('id')
                ->on('currency_attribute')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rate');
    }
};
