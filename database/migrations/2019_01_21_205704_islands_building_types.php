<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IslandsBuildingTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('islands_building_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stage');
            $table->timestamps();
        });

        Schema::table('islands_building_types', function (Blueprint $table) {
            $table->unsignedInteger('island_id')->after('id');
            $table->foreign('island_id')->references('id')->on('islands')->onDelete('cascade');
        });

        Schema::table('islands_building_types', function (Blueprint $table) {
            $table->unsignedInteger('building_type_id')->after('id');
            $table->foreign('building_type_id')->references('id')->on('building_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('islands_building_types', function (Blueprint $table) {
            $table->dropForeign(['building_type_id']);
            $table->dropColumn('building_type_id');
        });

        Schema::table('islands_building_types', function (Blueprint $table) {
            $table->dropForeign(['island_id']);
            $table->dropColumn('island_id');
        });

        Schema::dropIfExists('islands_building_types');
    }
}
