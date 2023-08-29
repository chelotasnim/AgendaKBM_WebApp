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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');
            $table->string('hari', 6);
            $table->foreignId('jam_id')
                ->references('id')
                ->on('jams')
                ->onDelete('cascade');
            $table->foreignId('guru_mapel_id')
                ->references('id')
                ->on('guru_mapels')
                ->onDelete('cascade');
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
        Schema::dropIfExists('jadwals');
    }
};
