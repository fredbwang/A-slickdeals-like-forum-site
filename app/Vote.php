<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils\RecordActivity;

class Vote extends Model
{
    use RecordActivity;

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
