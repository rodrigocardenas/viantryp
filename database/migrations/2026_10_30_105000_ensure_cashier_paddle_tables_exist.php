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
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->morphs('billable');
                $table->string('paddle_id')->unique();
                $table->string('name');
                $table->string('email');
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->morphs('billable');
                $table->string('type');
                $table->string('paddle_id')->unique();
                $table->string('status');
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('paused_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscription_items')) {
            Schema::create('subscription_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id');
                $table->string('product_id');
                $table->string('price_id');
                $table->string('status');
                $table->integer('quantity');
                $table->timestamps();

                $table->unique(['subscription_id', 'price_id']);
            });
        }

        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->morphs('billable');
                $table->string('paddle_id')->unique();
                $table->string('paddle_subscription_id')->nullable()->index();
                $table->string('invoice_number')->nullable();
                $table->string('status');
                $table->string('total');
                $table->string('tax');
                $table->string('currency', 3);
                $table->timestamp('billed_at');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('subscription_items');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('customers');
    }
};

