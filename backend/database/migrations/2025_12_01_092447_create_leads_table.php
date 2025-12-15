<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
public function up()
{
    Schema::create('leads', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('email')->nullable()->change();
        $table->string('phone')->nullable();
        $table->string('ip')->nullable();
        $table->timestamps();
});
}


public function down()
    {
        Schema::dropIfExists('leads');
    }
};