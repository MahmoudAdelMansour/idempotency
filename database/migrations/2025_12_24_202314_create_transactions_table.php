<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string("bank_name");
            $table->text("bank_reference");
            $table->decimal("amount");
            $table->string("currency");
            $table->dateTime("date");
            $table->json("raw_payload");
            $table->unique(["bank_name", "bank_reference"]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
