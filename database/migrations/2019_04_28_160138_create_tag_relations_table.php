<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('img_id')->nullable()->default(NULL);
            $table->integer('tag_id')->nullable()->default(NULL);
            $table->integer('sort_num')->nullable()->default(NULL);
            //$table->integer('view_count')->nullable()->default(0);
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
        Schema::dropIfExists('tag_relations');
    }
}

