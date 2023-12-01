<?php

namespace Modules\DataProcessor\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DataProcessor\Database\factories\QuotaFactory;

class Quota extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'request_rate', 'volume_rate'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): QuotaFactory
    {
        return QuotaFactory::new();
    }
}
