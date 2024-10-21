<?php

use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
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
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->json('edited_contents')->nullable();
            $table->boolean('pinned')->default(false);
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Thought::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('replied')->default(false);
            $table->foreignIdFor(Reply::class, 'replied_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
