<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_imgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('user_id')->nullable()->default(NULL);
            $table->integer('cate_id')->nullable()->default(NULL);
            $table->string('img_path')->nullable()->default(NULL);
            $table->text('explain')->nullable()->default(NULL);
            $table->string('target_url')->nullable()->default(NULL);
            $table->integer('sort_num')->nullable()->default(NULL);
            $table->boolean('open_status')->nullable()->default(1);
            $table->integer('view_count')->nullable()->default(NULL);
            
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
        Schema::dropIfExists('user_imgs');
    }
}
