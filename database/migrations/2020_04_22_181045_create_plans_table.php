<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->boolean('membership_number');
            $table->boolean('import_membership_csv');
            $table->boolean('card_customization');
            $table->unsignedInteger('number_of_notifications');
            $table->unsignedInteger('number_of_announcements');
            $table->unsignedInteger('number_of_benefits');
            $table->unsignedInteger('number_of_members');
            $table->unsignedInteger('monthly_price');
            $table->unsignedInteger('yearly_price');
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
        Schema::dropIfExists('plans');
    }
}
