<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('license_key')->unique(); // Unique key for the license
            $table->string('license_type'); // Unique key for the license
            $table->string('plan'); // Plan type (e.g., Pro Lifetime)
            $table->string('quantity'); // Plan type (e.g., Pro Lifetime)
            $table->date('purchase_date'); // Date when the license was purchased
            $table->date('expires_at'); // Date when the license will expire
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active'); // License status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
