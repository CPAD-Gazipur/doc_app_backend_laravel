<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doc_id',
        'ratings',
        'reviews',
        'reviewed_by',
        'status',
    ];

    public function user(){
        return $this->belogaTo(User::class);
    }
}
