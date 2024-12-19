<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;



class Book extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * table
     *
     * @var string
     */
    protected $table = "books";

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'title',
        'author',
        'isbn',
        'publication_date',
        'availability_status',

    ];

    /**
     * Method boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });

        static::deleting(function ($book) {
            $book->borrowingRecords()->each(function ($borrow) {
                $borrow->delete();
            });
        });
    }

    /**
     * Method search
     *
     * @param $input $input [explicite description]
     * @param $availability_status $availability_status [explicite description]
     *
     * @return void
     */
    public static function search($input, $availability_status = null)
    {
        $query = self::whereRaw("to_tsvector('english', title || ' ' || author || ' ' || isbn)  @@ plainto_tsquery('english', ?)",  [$input]);
        if ($availability_status) {
            $query->where('availability_status', $availability_status);
        }
        return $query->get();
    }

    /**
     * Method borrowingRecords
     *
     * @return void
     */
    public function borrowingRecords()
    {
        return $this->hasMany(BorrowingRecord::class, 'book_id');
    }
}
