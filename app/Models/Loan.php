<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        "user_id",
        "book_id",
        "borrow_date",
        "return_date",
        "actual_return",
        "status",
        "late_fee"
    ];

    public function user(): BelongsTo

    {

        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo

    {

        return $this->belongsTo(Book::class);
    }


    protected function casts(): array

    {

        return [

            'status' => LoanStatus::class,

        ];
    }
}
