<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description',1024);
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('doctor_id');

            $table->dateTime('start_time');
            $table->string('meeting_id')->nullable();
            $table->string('passcode')->nullable();

            $table->boolean('approved')->default(false);
            $table->boolean('has_rejected')->default(false);
            $table->string('rejected_reason',1024)->nullable();

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
        Schema::dropIfExists('doctor_meetings');
    }
}
