<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('issue_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['assigned', 'creator']);
            
            // Foreign Keys
            $table->foreign('issue_id')->references('id')->on('issues');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeign('issues_assignee_id_foreign');
            $table->dropColumn('assignee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_user');
        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedBigInteger('assignee_id')->nullable();
            // Foreign Keys
            $table->foreign('assignee_id')->references('id')->on('users');
        });
    }
}
