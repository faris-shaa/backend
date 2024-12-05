<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('organizer_details', function (Blueprint $table) {
            $table->id(); // auto-incrementing id (equivalent to int(11) NOT NULL AUTO_INCREMENT)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assuming user_id references the 'users' table
            $table->string('arabic_name', 225)->nullable();
            $table->string('facebook', 225)->nullable();
            $table->string('twitter', 225)->nullable();
            $table->string('instagram', 225)->nullable();
            $table->longText('short_description_english')->nullable();
            $table->longText('short_description_arabic')->nullable();
            $table->longText('long_description_english')->nullable();
            $table->longText('long_description_arabic')->nullable();
            $table->timestamps(0); // equivalent to created_at and updated_at with no fraction
            $table->time('deleted_at')->nullable(); // deleted_at as TIME type, assuming you want this format
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizer_details');
    }
}
