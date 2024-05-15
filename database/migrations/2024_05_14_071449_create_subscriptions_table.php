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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // relation avec l'utilisateur 
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // relation avec la souscription par défaut 
            $table->unsignedBigInteger('defaultSub_id')->nullable();
            $table->foreign('defaultSub_id')->references('id')->on('default_subscriptions')->onDelete('set null');
            
            $table->string('service_name');
            $table->string('logo');
            $table->decimal('amount');
            $table->date('start_on');
            $table->date('end_on');
            $table->string('payment_method')->nullable();
            $table->string('cycle');
            $table->integer('plan_type')->nullable();
            $table->integer('reminder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
    
};
