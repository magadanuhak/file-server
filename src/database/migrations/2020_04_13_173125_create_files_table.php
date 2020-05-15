<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('files', static function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->morphs('attachable');

            $table->string('original_name')->index();
            $table->string('saved_name');
            $table->integer('size');
            $table->string('mime_type')->nullable();

            $table->unsignedInteger('author_id')->comment('Автор');
            $table->foreign('author_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
}
