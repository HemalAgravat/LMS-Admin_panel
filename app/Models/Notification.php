<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;



    /**
     * connection
     *
     * @var string
     */
    protected $connection = 'mongodb';
    /**
     * collection
     *
     * @var string
     */
    protected $collection = 'notification';
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['book_id', 'user_id', 'due_date', 'is_sent', 'penalty'];
}
