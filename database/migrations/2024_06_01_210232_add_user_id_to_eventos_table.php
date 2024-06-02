<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(); // Agrega la columna user_id sin la restricción
        });
    }

    public function down()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};


