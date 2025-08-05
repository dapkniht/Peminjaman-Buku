<?php

use App\Enums\LoanStatus;
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

        Schema::create('loans', function (Blueprint $table) {
            $statuses = array_map(fn($status) => $status->value, LoanStatus::cases());
            $table->uuid("id")->primary();
            $table->uuid("user_id");
            $table->uuid("book_id");
            $table->date("borrow_date");
            $table->date("return_date");
            $table->date("actual_return")->nullable(true);
            $table->enum("status", $statuses)->default(LoanStatus::Borrowed->value);
            $table->integer("late_fee")->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
