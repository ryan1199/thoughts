<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reply extends Model
{
    /** @use HasFactory<\Database\Factories\ReplyFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'content', 'edited_contents', 'pinned', 'user_id', 'thought_id', 'replied', 'replied_id'
    ];

    protected function pinned(): Attribute
    {
        return Attribute::make(
            get: function (bool $value) {
                return $value ? 'Pinned' : 'Unpinned';
            },
            set: function (bool $value) {
                return $value ? true : false;
            }
        );
    }
    protected function replied(): Attribute
    {
        return Attribute::make(
            get: function (bool $value) {
                return $value ? 'Replied' : 'Unreplied';
            },
            set: function (bool $value) {
                return $value ? true : false;
            }
        );
    }
    protected function casts(): array
    {
        return [
            'edited_contents' => 'array',
        ];
    }
    public function scopeContent($query, $content)
    {
        return $query->where('content', 'LIKE', '%' . $content. '%');
    }
    public function scopePinned($query, $pinned)
    {
        return $query->where('pinned', (bool) $pinned);
    }
    public function scopeReplied($query, $replied)
    {
        return $query->orWhere('replied', (bool) $replied);
    }
    public function scopeRepliedId($query, $replied_id)
    {
        return $query->orWhere('replied_id', (bool) $replied_id);
    }
    public static function generateSlug()
    {
        $slug = 'R' . now()->year . now()->month . now()->day;
        $replies = Reply::where('slug', 'like', '%' . $slug . '%')->count();
        if ($replies > 0) {
            $slug.= $replies + 1;
        } else {
            $slug.= '1';
        }
        return $slug;
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function thought(): BelongsTo
    {
        return $this->belongsTo(Thought::class);
    }
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class, 'replied_id')->with(['user', 'thought', 'reply', 'replies']);
    }
    public function reply(): BelongsTo
    {
        return $this->belongsTo(Reply::class,'replied_id')->with(['user', 'thought']);
    }
}
