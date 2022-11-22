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
        Schema::create('task_list_task_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('task_status_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('color', 20)->nullable();
        });

        Schema::table('task_statuses', function (Blueprint $table) {
            $table->dropColumn('task_list_id');
            $table->dropColumn('color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_list_task_status');

        Schema::table('task_statuses', function (Blueprint $table) {
            $table->bigInteger('task_list_id')->unsigned()->nullable()->after('name');
            $table->string('color', 20)->nullable()->after('task_list_id');
        });
    }
};
