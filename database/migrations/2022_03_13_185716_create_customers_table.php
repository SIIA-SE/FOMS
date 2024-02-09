<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender', 6);
            $table->string('nic_no', 12)->nullable();
            $table->string('address')->nullable();
            $table->string('contact_no', 10)->nullable();
            $table->string('email')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ds_division')->nullable();
            $table->string('gn_division')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('customers');
    }
}
