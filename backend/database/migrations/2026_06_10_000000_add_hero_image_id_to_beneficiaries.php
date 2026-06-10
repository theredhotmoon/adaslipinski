<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('beneficiaries', function (Blueprint $table) {
            // No DB-level FK constraint — SQLite can't ALTER one in, and the app
            // validates exists:media,id. A dangling id just resolves to null.
            $table->unsignedBigInteger('hero_image_id')->nullable()->after('cta_bar_label');
        });
    }

    public function down(): void {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn('hero_image_id');
        });
    }
};
