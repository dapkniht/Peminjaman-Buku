<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        "title",
        "category_id",
        "author_id",
        "isbn",
        "stock",
        "cover_url",

    ];

    public function author(): BelongsTo

    {

        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo

    {

        return $this->belongsTo(Category::class);
    }

    public function loans(): HasMany

    {

        return $this->hasMany(Loan::class);
    }
}
