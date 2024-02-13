<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class MessageModel extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $fillable = [
        'user_id',
        'content',
        'message_to',
        'HasSeen',
        'Seen_at',
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'message_to', 'id');
    }
}
