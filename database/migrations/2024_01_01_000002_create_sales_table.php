<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('invoice_number')->unique();
            $table->timestamps();
            $table->integer('created_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};