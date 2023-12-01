<?php

namespace Modules\DataProcessor\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DataProcessor\Database\factories\ProcessHistoryFactory;

class ProcessHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'object_uuid', 'object_volume'];


    protected static function newFactory(): ProcessHistoryFactory
    {
        return ProcessHistoryFactory::new();
    }
}
