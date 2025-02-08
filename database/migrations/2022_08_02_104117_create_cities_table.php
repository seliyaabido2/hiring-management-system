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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade')->onUpdate('cascade');
            $table->string('state_code');
            $table->string('country_id');
            $table->string('country_code');
            $table->decimal('latitude',$precision = 16, $scale = 2)->default(0);
            $table->decimal('longitude',$precision = 16, $scale = 2)->default(0);
            $table->timestamps();
            $table->boolean('flag')->default(1)->comment('1 = active, 0 = deactive');
            $table->string('wikiDataId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
