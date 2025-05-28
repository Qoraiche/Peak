<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->string('version'); // Version of the release (e.g., v1.0.0)
            $table->string('title'); // Title of the release (e.g., "Initial Release")
            $table->text('description')->nullable(); // Description of the release (can be empty if not available)
            $table->date('release_date'); // Date the release was made
            $table->string('download_url'); // URL to download the release (can be a GitHub link or direct download link)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
