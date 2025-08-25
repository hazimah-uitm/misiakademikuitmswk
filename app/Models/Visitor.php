<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Visitor extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'response_at',
        'full_name',
        'phone',
        'email',
        'program_bidang',
        'lokasi',
    ];

    protected $dates = [
        'response_at',
        'created_at',
        'updated_at',
    ];
}
