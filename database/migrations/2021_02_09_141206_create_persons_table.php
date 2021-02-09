<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('surname');
            $table->enum('gender', ['male', 'female']);
            $table->date('birthdate');
            $table->foreignId('partner_id')->nullable()->references('id')->on('persons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->references('id')->on('persons')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('persons');
    }
}
