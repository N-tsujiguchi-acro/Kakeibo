<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('kakeibos', function (Blueprint $table) {
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kakeibos', function (Blueprint $table) {
            //
        });
    }
};
