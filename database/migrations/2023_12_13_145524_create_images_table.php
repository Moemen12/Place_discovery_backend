<?php

use App\Models\Image;
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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('review_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('trip_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('image_url')->nullable();
            $table->enum('image_for', Image::$image_usages);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
