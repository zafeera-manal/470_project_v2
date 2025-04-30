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
        Schema::create('group_trip_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_trip_id')->constrained();
            $table->foreignId('friend_id')->constrained('users');  // User receiving the invitation
            $table->enum('status', ['pending', 'confirmed', 'declined'])->default('pending');  // Invitation status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_trip_invitations');
    }
};
