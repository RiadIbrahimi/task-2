<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timelog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'issue_id', 'user_id', 'seconds_logged'
    ];

    /**
     * The user that belong to the timelogs.
     */
    public function user()
    {
        return $this->belogsTo('App\User');
    }
}
