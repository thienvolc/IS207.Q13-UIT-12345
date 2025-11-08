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
        // Blog posts table
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id('blogpost_id');
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('slug')->unique();
            $table->string('thumb')->nullable();
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->string('conclusion')->nullable();
            $table->tinyInteger('status')->default(1); // 1=draft, 2=published, 3=archived
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Transactions table
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('order_id')->nullable()->constrained('orders', 'order_id')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->text('content')->nullable();
            $table->string('code')->nullable();
            $table->tinyInteger('type')->default(1); // 1=payment, 2=refund
            $table->string('mode')->nullable();
            $table->tinyInteger('status')->default(1); // 1=initiated, 2=pending, 3=success, 4=failed, 5=cancelled, 6=expired
            $table->unsignedInteger('version')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // Configs table
        Schema::create('configs', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('value')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('desc')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('blog_posts');
    }
};
