<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyGoalsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_goal_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->unsignedSmallInteger('display_order')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('weekly_goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 150);
            $table->enum('completion_type', ['boolean', 'integer', 'decimal']);
            $table->decimal('completion_value')->nullable();
            $table->unsignedSmallInteger('display_order')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('weekly_goal_list_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('weekly_goal_list_id')->references('id')->on('weekly_goal_lists');
            $table->timestamps();
        });

        Schema::create('weekly_goal_sets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('start');
            $table->datetime('end');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('weekly_goal_set_goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('complete')->default(0);
            $table->decimal('completion_value')->nullable();
            $table->decimal('progress_value')->nullable();
            $table->unsignedBigInteger('weekly_goal_id');
            $table->unsignedBigInteger('weekly_goal_set_id');
            $table->foreign('weekly_goal_set_id')->references('id')->on('weekly_goal_sets');
            $table->foreign('weekly_goal_id')->references('id')->on('weekly_goals');
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
        Schema::dropIfExists('weekly_goal_lists');
        Schema::dropIfExists('weekly_goals');
        Schema::dropIfExists('weekly_goal_sets');
        Schema::dropIfExists('weekly_goal_set_goals');
    }
}
