<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils\RecordActivity;

class Vote extends Model
{
    use RecordActivity;

    public static function boot()
    {
        parent::boot();

        // static::updated(function ($subject) {
        //     $subject->recordActivity('updated');
        // });
    }

    /**
     * @Override getEventsToRecord in RecordActivity trait
     *
     * @return array
     */
    protected static function getEventsToRecord()
    {
        return ['created', 'updated'];
    }

    protected $guarded = [];

    public function voted()
    {
        return $this->morphTo();
    }
}
