<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sounds', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->index();
            $table->string('name');
            $table->string('sound_url');
            $table->unsignedInteger('number_reproductions')->default(0);
            $table->unsignedInteger('duration_seconds');
            $table->json('credits')->nullable();
            $table->foreignId('album_id')->constrained('albums')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sounds');
    }
};
