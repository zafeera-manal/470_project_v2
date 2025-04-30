<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('city_stops', function (Blueprint $table) {
            $table->id();  // automatically creates the 'id' column
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('itinerary_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        
    }
    
    public function down()
    {
        Schema::dropIfExists('city_stops');
    }
    
};
