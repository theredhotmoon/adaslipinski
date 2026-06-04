<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('year_summaries', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->primary();
            $table->unsignedInteger('received_pln')->default(0);
            $table->unsignedInteger('spent_pln')->default(0);
            $table->unsignedInteger('balance_pln')->default(0);
            $table->unsignedInteger('tax_1_5_pln')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('year_summaries'); }
};
