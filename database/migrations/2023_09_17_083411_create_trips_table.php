<?php

use App\Models\User;
use App\Models\Driver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Driver::class)->nullable();
            $table->foreignIdFor(User::class);
            $table->boolean('is_started')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->json('origin')->nullable();
            $table->json('destination')->nullable();
            $table->string('destination_name')->nullable();
            $table->json('driver_location')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
