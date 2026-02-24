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
     Schema::create('memberships', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('colocation_id')->constrained();
    $table->enum('role',['owner','member']);
    $table->timestamp('joined_at')->nullable();
    $table->timestamp('left_at')->nullable();
    $table->integer('reputation_score')->default(0);
    $table->unique(['user_id','colocation_id']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
