<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('email')->unique();
            $table->integer('permission')->nullable()->default(NULL);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        
        DB::table('admins')->insert([
                'name' => 'oniwa-admin',
                'email' => 'oniwa@oniwa.com',
                'permission'=> 1,
                'password' => bcrypt('sankan4on'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        );
        
        DB::table('admins')->insert([
                'name' => 'opal',
                'email' => 'opal@frank.fam.cx',
                'permission'=> 1,
                'password' => bcrypt('aaaaa111'),
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
        Schema::dropIfExists('admins');
    }
}
