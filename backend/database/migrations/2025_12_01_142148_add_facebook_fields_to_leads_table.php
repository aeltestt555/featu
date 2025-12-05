<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('facebook_lead_id')->nullable()->after('id');
            $table->string('form_id')->nullable()->after('facebook_lead_id');
            $table->string('ad_id')->nullable()->after('form_id');
            $table->string('page_id')->nullable()->after('ad_id');
            $table->json('data')->nullable()->after('page_id');
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_lead_id',
                'form_id',
                'ad_id',
                'page_id',
                'data'
            ]);
        });
    }
};

