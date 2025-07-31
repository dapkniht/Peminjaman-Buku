<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanCart extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = "loan_carts";

    protected $fillable = [
        "user_id",
        "book_id",
        "added_at"
    ];
}
