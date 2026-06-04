<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('foundations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('krs')->nullable();
            $table->string('nip')->nullable();
            $table->string('regon')->nullable();
            $table->string('cel')->nullable();
            $table->string('address')->nullable();
            $table->string('web')->nullable();
            $table->string('blik_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('foundations'); }
};
