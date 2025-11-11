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
        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('level')->default(1);
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('slug')->unique();
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id('tag_id');
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('slug')->unique();
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Product-Category pivot table
        Schema::create('product_categories', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories', 'category_id')->onDelete('cascade');
            $table->primary(['product_id', 'category_id']);
        });

        // Product-Tag pivot table
        Schema::create('product_tags', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags', 'tag_id')->onDelete('cascade');
            $table->primary(['product_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};
