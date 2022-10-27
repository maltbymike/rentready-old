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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('details')->nullable();
            $table->bigInteger('task_status_id')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_statuses');
    }
};
