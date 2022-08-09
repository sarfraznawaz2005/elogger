<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', static function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('project_id')->index();
            $table->string('todolist_id')->index();
            $table->string('todo_id')->index();
            $table->date('dated');
            $table->string('time_start');
            $table->string('time_end');
            $table->string('description');
            $table->enum('status', ['pending', 'posted'])->default('pending');
            $table->string('time_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
