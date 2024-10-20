<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Thought extends Model
{
    /** @use HasFactory<\Database\Factories\ThoughtFactory> */
    use HasFactory;
    
    protected $fillable = [
        'slug', 'topic', 'content', 'tags', 'open', 'user_id',
    ];

    protected function topic(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                return Str::title($value);
            },
            set: function (string $value) {
                return Str::title($value);
            }
        );
    }
    protected function tags(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                return Str::of($value)->explode(' ');
            },
            set: function (string $value) {
                $tags = Str::of($value)->explode(' ');
                $tags = Arr::sort($tags);
                return implode(' ', $tags);
            }
        );
    }
    protected function open(): Attribute
    {
        return Attribute::make(
            get: function (bool $value) {
                return $value ? 'Open' : 'Close';
            },
            set: function (bool $value) {
                return $value ? true : false;
            }
        );
    }
    public function scopeTopic($query, $topic)
    {
        return $query->where('topic', 'LIKE', '%' . $topic . '%');
    }
    public function scopeContent($query, $content)
    {
        return $query->where('content', 'LIKE', '%' . $content. '%');
    }
    public function scopeTags($query, $tags)
    {
        $tags = Str::of($tags)->explode(',');
        $tags = Arr::sort($tags);
        return $query->where(function ($query) use ($tags) {
            foreach ($tags as $tag) {
                $query->where('tags', 'LIKE', '%'. $tag. '%');
            }
        });
    }
    public function scopeOpen($query, $open)
    {
        return $query->where('open', (bool) $open);
    }
    public static function generateSlug()
    {
        $slug = 'T' . now()->year . now()->month . now()->day;
        $thoughts = Thought::where('slug', 'like', '%' . $slug . '%')->count();
        if ($thoughts > 0) {
            $slug.= $thoughts + 1;
        } else {
            $slug.= '1';
        }
        return $slug;
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
