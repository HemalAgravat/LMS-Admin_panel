<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, HasFactory, Notifiable;


    /**
     * table
     *
     * @var string
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['uuid', 'name', 'email', 'password', 'role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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

        static::deleting(function ($user) {
            $user->borrowingRecords()->each(function ($borrow) {
                $borrow->delete();
            });
        });
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
        $formattedQuery = str_replace(' ', ' & ', [$input]);
        return self::whereRaw("to_tsvector('english', name || ' ' || email || ' ' || role) @@ to_tsquery(?)", [$formattedQuery]);
    }

    /**
     * Method borrowingRecords
     *
     * @return void
     */
    public function borrowingRecords()
    {
        return $this->hasMany(BorrowingRecord::class, 'user_id');
    }
}
