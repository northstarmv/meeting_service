<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingTrainerClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_trainer_clients', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description',1024);
            $table->unsignedBigInteger('trainer_id');
            $table->json('clients');
            $table->dateTime('start_time');
            $table->string('meeting_id');
            $table->string('passcode');

            $table->boolean('finished')->default(false);
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
        Schema::dropIfExists('meeting_trainer_clients');
    }
}
