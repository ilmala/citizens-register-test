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
        Schema::create('family_person', function (Blueprint $table) {
            $table->foreignUlid('family_id')->index()->constrained();
            $table->foreignUlid('person_id')->index()->constrained();
            $table->string('role');

            $table->primary(['family_id', 'person_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_person');
    }
};
