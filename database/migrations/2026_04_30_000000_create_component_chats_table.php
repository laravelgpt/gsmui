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
        Schema::create('component_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('component_chats')->onDelete('cascade');
            $table->text('message');
            $table->string('context', 500)->nullable();
            $table->string('type')->default('user'); // user, assistant, recording
            $table->string('category')->default('general'); // suggestion, general, template
            $table->string('template_category')->nullable();
            $table->json('template_data')->nullable();
            $table->string('attachment_path')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'type', 'category']);
            $table->index(['parent_id']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_chats');
    }
};
