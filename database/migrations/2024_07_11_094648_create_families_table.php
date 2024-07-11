<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->string('name', 100);

            $table
                ->foreignUlid('responsible_id')
                ->index()
                ->nullable()
                ->constrained('members');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
