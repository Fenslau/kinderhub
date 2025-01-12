<?php

use App\Enums\AnnouncementTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('care_category_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('type')->default(AnnouncementTypeEnum::PROVIDE);
            $table->json('sub_category')->nullable();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_global')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
