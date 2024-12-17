<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWhatsappTermsColumnsToOrganizerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizer_details', function (Blueprint $table) {
            $table->string('whatsapp', 225)->nullable()->after('instagram');
            $table->longText('terms_english')->nullable()->after('whatsapp');
            $table->longText('terms_arabic')->nullable()->after('terms_english');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('organizer_details', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
            $table->dropColumn('terms_english');
            $table->dropColumn('terms_arabic');
        });
    }
}
