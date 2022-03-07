<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

//        Schema::table('USERS', function (Blueprint $table) {
//            $table->boolean('RECEIVEEMAILS')->default(false)->change();
//            $table->boolean('SHARING')->default(false)->change();
//            $table->char('USERURL',256)->nullable()->change();
//            $table->char('USERNAME',100)->nullable()->change();
//            $table->char('USERIMAGE_DATACOLLECTIONEVENTID_OID',255)->nullable()->change();
//            $table->text('SALT')->nullable()->change();
//        });
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
