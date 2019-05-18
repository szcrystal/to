<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('name')->nullable()->default(NULL);
            //$table->string('email')->unique();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('icon_img_path')->nullable()->default(NULL);
            $table->text('profile')->nullable()->default(NULL);
            $table->string('password');
            $table->boolean('active')->nullable()->default(NULL);
            
            $table->rememberToken();
            $table->timestamps();
        });
        
        
        //DB::statement('ALTER TABLE users CHANGE post_num post_num INT(7) UNSIGNED ZEROFILL');
        
        DB::table('users')->insert([
                'name' => 'gr-user',
                'email' => 'gr@gr.com',
                'password' => bcrypt('grgrgrgr'),
                
                'active' => 1,
                
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        );
        
        DB::table('users')->insert([
                'name' => 'opal',
                'email' => 'opal@frank.fam.cx',
                'password' => bcrypt('aaaaa111'),
                'active' => 1,

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
        Schema::dropIfExists('users');
    }
}
