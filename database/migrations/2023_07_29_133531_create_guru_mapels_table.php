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
        Schema::create('guru_mapels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')
                ->references('id')
                ->on('guru')
                ->onDelete('cascade');
            $table->foreignId('mapel_id')
                ->references('id')
                ->on('mapel')
                ->onDelete('cascade');
            $table->integer('guru_mapel')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('guru_mapels');
    }
};
