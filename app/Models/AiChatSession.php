<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    protected $fillable = [
        'title',
        'customer_id'
    ];

    public function messages()
    {
        return $this->hasMany(AiMessage::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
