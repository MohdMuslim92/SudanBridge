<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->unsigned();
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('recipient_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->BigInteger('status_id')->unsigned()->default(1); // Set default value to 1
            $table->string('tracking_token');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('senders')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('recipients')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
