<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('is_admin')->default(0);
            $table->string('basecamp_org_id')->nullable();
            $table->string('basecamp_org')->nullable();
            $table->string('basecamp_api_key')->nullable();
            $table->string('basecamp_api_user_id')->index()->nullable();
            $table->string('working_hours_count')->default(8);
            $table->string('holidays_count')->default(0);
            $table->boolean('reg_celeb')->default(0);
            $table->text('calculations')->nullable();
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
        Schema::dropIfExists('users');
    }
};
