<?php

namespace App\Models;

use Carbon\Carbon;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Reply extends Model
{
    /** @use HasFactory<\Database\Factories\ReplyFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'content', 'edited_contents', 'pinned', 'user_id', 'thought_id', 'replied', 'replied_id'
    ];

    protected function content(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $contents = [];
                $markdown_content = Str::of($value)->markdown([
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                ]);
                $contents['content'] = $value;
                $contents['markdown_content'] = $markdown_content;
                return $contents;
            },
            set: function (string $value) {
                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $content = $purifier->purify($value);
                return $content;
            }
        );
    }
    protected function editedContents(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                if ($value != null) {
                    $edited_contents = [];
                    $json_of_edited_contents = json_decode($value, true);
                    foreach ($json_of_edited_contents as $json_of_edited_content) {
                        $edited_content = [
                            'content' => $json_of_edited_content['content'],
                            'markdown_content' => Str::of($json_of_edited_content['content'])->markdown([
                                'html_input' => 'strip',
                                'allow_unsafe_links' => false,
                            ]),
                            'updated_at' => Carbon::parse($json_of_edited_content['updated_at'])
                        ];
                        $edited_contents[] = $edited_content;
                    }
                    return $edited_contents;
                } else {
                    return null;
                }
            },
            set: function (array $value) {
                return count($value) > 0 ? json_encode($value) : null;
            }
        );
    }
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
    // protected function casts(): array
    // {
    //     return [
    //         'edited_contents' => 'array',
    //     ];
    // }
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
    public static function store(User $user, Thought $thought, $replied_id, $content)
    {
        $success = false;
        $reply = new Reply;
        $success = DB::transaction(function () use ($user, $thought, $replied_id, $content, &$reply) {
            $reply->slug = $reply->generateSlug();
            $reply->content = $content;
            $reply->edited_contents = [];
            $reply->pinned = false;
            $reply->user_id = $user->id;
            $reply->thought_id = $thought->id;
            $reply->replied = false;
            if ($replied_id) {
                $replied_reply = Reply::findOrFail($replied_id);
                $replied_reply->update([
                    'replied' => true
                ]);
                $reply->replied_id = $replied_id;
            }
            $reply->save();
            return $reply;
        }, 10);
        if ($success) {
            return $reply;
        } else {
            return false;
        }
    }
    public static function updateReply(Reply $reply, $content, $edited_contents)
    {
        $success = DB::transaction(function () use ($reply, $content, $edited_contents) {
            $reply->content = $content;
            $reply->edited_contents = $edited_contents;
            $reply->updated_at = now();
            $reply->save();
            return $reply;
        }, 10);
        if ($success) {
            return $reply;
        } else {
            return false;
        }
    }
}
