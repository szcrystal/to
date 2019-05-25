<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->default(NULL);
            $table->string('slug')->unique()->nullable()->default(NULL);
            $table->string('link_name')->nullable()->default(NULL);
            $table->boolean('open_status')->nullable()->default(1);
			$table->integer('view_count')->nullable()->default(0);
            $table->timestamps();
        });
        
        
        DB::table('categories')->insert([
                'name' => '常緑樹',
                'slug' => 'jyoryokujyu',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        );
        
        DB::table('categories')->insert([
                'name' => '花壇',
                'slug' => 'kadan',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
