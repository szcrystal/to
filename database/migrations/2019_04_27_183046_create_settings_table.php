<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('admin_name')->nullable()->default(NULL);
            $table->string('admin_email')->nullable()->default(NULL);
            $table->text('mail_footer')->nullable()->default(NULL);
            $table->text('mail_user')->nullable()->default(NULL);
            
            $table->boolean('is_product')->nullable()->default(NULL);

            
//            $table->string('meta_title')->nullable()->default(NULL);
//            $table->text('meta_description')->nullable()->default(NULL);
//            $table->string('meta_keyword')->nullable()->default(NULL);
            
            $table->string('fix_need')->nullable()->default(NULL);
            $table->string('fix_other')->nullable()->default(NULL);
            
            $table->text('analytics_code')->nullable()->default(NULL);
            
            $table->timestamps();
        });
        
        
        DB::table('settings')->insert([
                'admin_name' => 'GREEN ROCKET',
                'admin_email' => 'bonjour@frank.fam.cx',

				'is_product' => 0,

                
                'fix_need' => '1,2,3,4',
                
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
        Schema::dropIfExists('settings');
    }
}
