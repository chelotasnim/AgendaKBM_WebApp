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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');
            $table->string('nama_jadwal', 100)->unique();
            $table->string('deskripsi_jadwal')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('hidden')->nullable();
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
        Schema::dropIfExists('jadwal');
    }
};
