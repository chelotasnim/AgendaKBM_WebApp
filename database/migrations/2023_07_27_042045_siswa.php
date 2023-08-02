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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');
            $table->string('name', 100)->unique();
            $table->string('username', 25)->unique();
            $table->string('email')->unique();
            $table->string('password');
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
        Schema::dropIfExists('siswa');
    }
};
