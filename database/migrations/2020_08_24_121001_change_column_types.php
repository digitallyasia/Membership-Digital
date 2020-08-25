<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->text('body')->change();
        });
        Schema::table('benefits', function (Blueprint $table) {
            $table->text('details')->change();
        });
        Schema::table('announcements', function (Blueprint $table) {
            $table->text('details')->change();
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->text('tnc')->nullable()->change();
            $table->text('pp')->nullable()->change();
            $table->text('faq')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('body')->change();
        });
        Schema::table('benefits', function (Blueprint $table) {
            $table->string('details')->change();
        });
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('details')->change();
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('tnc')->nullable()->change();
            $table->string('pp')->nullable()->change();
            $table->string('faq')->nullable()->change();
        });
    }
}
