<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->enum('type', ['bug', 'enhancement', 'proposal', 'task']);
            $table->enum('priority', ['trivial', 'minor', 'major', 'critical', 'blocker']);
            $table->enum('status', ['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])->nullable()->default('open');
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('assignee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
