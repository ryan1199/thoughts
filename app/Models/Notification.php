<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'content', 'read', 'links', 'user_id',
    ];

    protected function content(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $user = $this->links['user'];
                $thought = $this->links['thought'];
                $reply = Arr::exists($this->links,'reply') ? $this->links['reply'] : false;
                if ($reply) {
                    $content = $user->name.' '.$value.' "'.Str::words($reply->content, 3, '...').'" on thought "'.$thought->topic.'"';
                } else {
                    $content = $user->name.' '.$value.' "'.Str::words($thought->topic, 3, '...').'"';
                }
                return $content;
            },
            set: function (string $value) {
                return $value;
            }
        );
    }
    protected function links(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $links = $this->castAttribute('links', $value);
                $links['user'] = User::where('slug', $links['user'])->first();
                $links['thought'] = Thought::where('slug', $links['thought'])->first();
                if (Arr::exists($links, 'reply')) {
                    $links['reply'] = Reply::where('slug', $links['reply'])->first();
                }
                return $links;
            }
        );
    }
    protected function casts(): array
    {
        return [
            'read' => 'boolean',
            'links' => 'array',
        ];
    }
    public static function generateSlug()
    {
        $slug = 'N' . now()->year . now()->month . now()->day;
        $notifications = Notification::where('slug', 'like', '%' . $slug . '%')->count();
        if ($notifications > 0) {
            $slug.= $notifications + 1;
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
