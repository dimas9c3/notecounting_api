<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('label')->nullable();
            $table->unsignedInteger('type');
            $table->unsignedInteger('status')->nullable();
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('tb_user_notes');
    }
}
