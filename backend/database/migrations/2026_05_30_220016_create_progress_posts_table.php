<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('progress_posts', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();
            $table->string('title');
            $table->text('body');
            $table->foreignId('image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('image_alt')->nullable();
            $table->unsignedInteger('amount_pln')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('progress_posts'); }
};
