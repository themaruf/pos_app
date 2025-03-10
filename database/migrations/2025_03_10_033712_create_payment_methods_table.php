<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert default payment methods
        \DB::table('payment_methods')->insert([
            ['name' => 'Cash'],
            ['name' => 'Card'],
            ['name' => 'bKash'],
            ['name' => 'Nagad'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
