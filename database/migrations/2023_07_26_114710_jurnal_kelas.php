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
        Schema::create('jurnal_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('kelas', 11);
            $table->foreignId('guru_id')
                ->references('id')
                ->on('guru')
                ->onDelete('cascade');
            $table->foreignId('mapel_id')
                ->references('id')
                ->on('mapel')
                ->onDelete('cascade');
            $table->string('tanggal', 50);
            $table->string('jam_mulai', 5);
            $table->string('jam_selesai', 5);
            $table->tinyInteger('total_siswa');
            $table->tinyInteger('tidak_hadir');
            $table->string('materi');
            $table->timestamps();
            $table->integer('action_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal_kelas');
    }
};
