<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('item_orders', function (Blueprint $table) {
            $table->id();
            $table->string('username'); // Username Roblox
            $table->string('wa_number'); // WhatsApp number
            $table->string('email')->nullable(); // Email (optional)
            $table->string('payment_method'); // Payment method
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Foreign key to 'items' table
            $table->foreignId('game_id')->constrained()->onDelete('cascade'); // Foreign key to 'games' table
            $table->decimal('item_price', 10, 2); // Harga item
            $table->string('item_name'); // Nama item
            $table->string('game_name'); // Nama game
            $table->decimal('total_price', 10, 2); // Total price of the order
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed'])->default('pending'); // Order status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_orders');
    }
}
