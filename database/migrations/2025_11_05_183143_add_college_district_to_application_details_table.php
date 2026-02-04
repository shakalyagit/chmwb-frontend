<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('application_details', function (Blueprint $table) {
            $table->string('college_district')->nullable()->after('college');
        });
    }

    public function down()
    {
        Schema::table('application_details', function (Blueprint $table) {
            $table->dropColumn('college_district');
        });
    }
};
