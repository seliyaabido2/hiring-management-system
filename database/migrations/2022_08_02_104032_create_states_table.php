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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->string('country_code');
            $table->string('fips_code');
            $table->string('iso2');
            $table->string('type');
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
        Schema::dropIfExists('states');
    }
};
