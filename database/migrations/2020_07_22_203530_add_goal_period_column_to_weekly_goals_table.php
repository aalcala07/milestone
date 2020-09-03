<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoalPeriodColumnToWeeklyGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weekly_goals', function (Blueprint $table) {
            $table->unsignedBigInteger('goal_period_id')->nullable();
            $table->foreign('goal_period_id')->references('id')->on('goal_periods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weekly_goals', function (Blueprint $table) {
            //
        });
    }
}
