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
        // User profiles table
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('avatar')->nullable();
            $table->text('profile')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('last_login')->nullable();
        });

        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('name');
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });

        // User-Role pivot table
        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles', 'role_id')->onDelete('cascade');
            $table->primary(['user_id', 'role_id']);
        });

        // Product metas table
        Schema::create('product_metas', function (Blueprint $table) {
            $table->id('meta_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->string('key');
            $table->text('content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_metas');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_profiles');
    }
};
