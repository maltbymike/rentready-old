<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_lists_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_list_id')->unsigned();
            $table->foreign('task_list_id')
                ->references('id')
                ->on('task_lists')->onDelete('cascade');
            $table->bigInteger('task_id')->unsigned();
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_lists_tasks');
    }
};
        