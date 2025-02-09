<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class BorrowingRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'borrowing_records';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'returned',
        'total_penalty',
        'penalty_paid',
        'paid_penalty_date'
    ];

    /**
     * Method boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->borrow_date = now();
        });
        static::saving(function ($borrow) {
            if ($borrow->due_date) {
                $borrow->due_date_text = Carbon::parse($borrow->due_date)->format('Y-m-d');
            } else {
                $borrow->due_date_text = null;
            }
        });
    }

    /**
     * Method user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Method book
     *
     * @return void
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Method search
     *
     * @param $input $input [explicite description]
     *
     * @return void
     */
    public static function search($input)
    {
        return self::with(['user', 'book'])
            ->whereRaw("to_tsvector('english', due_date_text || ' ' || (select title from books where id = borrowing_records.book_id)) @@ plainto_tsquery('english', ?)", [$input])
            ->orWhereHas('user', function ($q) use ($input) {
                $q->whereRaw("to_tsvector('english', name) @@ plainto_tsquery('english', ?)", [$input]);
            })
            ->get();
    }
}
