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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->json('author')->nullable();
            $table->json('businessComment')->nullable();
            $table->string('businessId')->nullable();
            $table->json('photos')->nullable();
            $table->integer('rating')->nullable();
            $table->json('reactions')->nullable();
            $table->string('reviewId')->nullable();
            $table->text('text')->nullable();
            $table->string('textLanguage')->nullable();
            $table->json('textTranslations')->nullable();
            $table->dateTime('updatedTime')->nullable();
            $table->json('videos')->nullable();

            $table->foreignId('subsidiary_id')->index()->constrained('subsidiaries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
