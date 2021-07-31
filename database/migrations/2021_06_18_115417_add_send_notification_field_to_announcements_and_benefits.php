<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendNotificationFieldToAnnouncementsAndBenefits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->boolean('send_notification')->default(true);
        });
        Schema::table('benefits', function (Blueprint $table) {
            $table->boolean('send_notification')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('send_notification');
        });
        Schema::table('benefits', function (Blueprint $table) {
            $table->dropColumn('send_notification');
        });
    }
}
