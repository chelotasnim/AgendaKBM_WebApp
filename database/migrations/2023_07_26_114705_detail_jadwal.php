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
        Schema::create('detail_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')
                ->references('id')
                ->on('jadwal')
                ->onDelete('cascade');
            $table->foreignId('mapel_id')
                ->references('id')
                ->on('mapel')
                ->onDelete('cascade');
            $table->foreignId('guru_id')
                ->references('id')
                ->on('guru')
                ->onDelete('cascade');
            $table->string('hari', 6);
            $table->string('jam_ke', 2);
            $table->string('jam_mulai', 5);
            $table->string('jam_selesai', 5);
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
        Schema::dropIfExists('detail_jadwal');
    }
};
