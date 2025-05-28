<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('model'); // The model being exported
            $table->string('name')->default('none'); // The export name
            $table->string('file_name'); // File name of the export
            $table->string('file_path')->nullable(); // Path when completed
            $table->string('format'); // xlsx, csv, pdf, etc.
            $table->json('columns')->nullable();
            $table->string('locale')->nullable();
            $table->json('ids')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
