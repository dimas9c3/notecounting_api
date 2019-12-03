<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotes extends Model
{
    public $timestamps = true;

    protected $table = 'tb_user_notes';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'title', 'description', 'label', 'type', 'status', 'due_date',
    ];
}
