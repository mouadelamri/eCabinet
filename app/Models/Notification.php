<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'type' , 'message', 'est_lu' , 'sent_at',
        'user_id'
    ];
    protected $casts = [
        'est_lu' => 'boolean',
        'sent_at' => 'datetime'
    ];
    //relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //methods
    public function markAsRead()
    {

    }
}

