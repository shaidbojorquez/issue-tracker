<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->timestamp('begin_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])->nullable()->default('open');
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
        Schema::dropIfExists('projects');
    }
}
