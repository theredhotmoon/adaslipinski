<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name');
            $table->unsignedSmallInteger('age')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('diagnosis_plain')->nullable();
            $table->string('hero_kicker')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_bar_label')->nullable();
            $table->boolean('recurring_default')->default(true);
            $table->unsignedInteger('nfz_monthly_pln')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('beneficiaries'); }
};
