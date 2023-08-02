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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenjang_kelas_id')
                ->references('id')
                ->on('jenjang_kelas')
                ->onDelete('cascade');
            $table->string('name', 12);
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
        Schema::dropIfExists('kelas');
    }
};
