<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'content', 'status', 'published_at', 'user_id'])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: function (string $value): array {
                if ($this->exists && $value === $this->title) {
                    return ['title' => $value];
                }

                return [
                    'title' => $value,
                    'slug' => Str::slug($value),
                ];
            },
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            set: function (string $value): array {
                if ($this->exists && $value === $this->status) {
                    return ['status' => $value];
                }

                if ($value == ArticleStatus::Published->value) {
                    return [
                        'status' => $value,
                        'published_at' => Carbon::now(),
                    ];
                }

                return [
                    'status' => $value,
                ];
            },
        );
    }
}
