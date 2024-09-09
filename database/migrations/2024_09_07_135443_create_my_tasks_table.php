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
        Schema::create('my_tasks', function (Blueprint $table) {
            $table->bigIncrements('task_id');
            $table->string('title');
            $table->enum('priority',['important','moderate_importance','normal']);
            $table->text('description');
            $table->enum('status',['in_progress','completed'])->nullable();
            $table->string('assigned_to')->nullable();//here i mean for example i but this task to user aya 
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade')->nullable(); 
            $table->date('due_date')->nullable(); 
            $table->string('added_by')->nullable();;//this is for who added the task admin or manager
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_tasks');
    }
};
