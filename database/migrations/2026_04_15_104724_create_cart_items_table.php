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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('cartable'); // package or service
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('time_slot_id')->nullable()->constrained()->onDelete('set null');
            $table->date('booking_date');
            $table->timestamps();

            $table->index('cart_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
