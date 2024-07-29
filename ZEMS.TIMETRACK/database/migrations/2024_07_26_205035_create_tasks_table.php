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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('author');
            $table->unsignedBigInteger('project_id');
            $table->text('description')->nullable();
            $table->boolean('is_done')->default(0);
            $table->timestamp('when_must_done')->nullable();
            $table->timestamp('when_done')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
