<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique(); $table->string('customer_name'); $table->string('email'); $table->string('phone', 30); $table->text('address');
            $table->string('payment_method', 30); $table->string('payment_status', 30)->default('pending'); $table->string('status', 30)->default('new');
            $table->unsignedInteger('subtotal'); $table->unsignedInteger('shipping_cost')->default(0); $table->unsignedInteger('total'); $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) { $table->id(); $table->foreignId('order_id')->constrained()->cascadeOnDelete(); $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete(); $table->string('name'); $table->unsignedInteger('price'); $table->unsignedInteger('quantity'); $table->unsignedInteger('subtotal'); $table->timestamps(); });
    }
    public function down(): void { Schema::dropIfExists('order_items'); Schema::dropIfExists('orders'); }
};
