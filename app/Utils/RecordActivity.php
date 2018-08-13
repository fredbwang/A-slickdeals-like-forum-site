<?php

namespace App\Utils;

use App\Activity;

trait RecordActivity
{
    static function bootRecordActivity()
    {
        if (!auth()->check()) return;
        
        foreach (static::getEventsToRecord() as $event) {
            static::$event(function ($subject) use ($event) {
                $subject->recordActivity($event);
            });
        }
    }

    protected static function getEventsToRecord()
    {
        return ['created'];
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    protected function recordActivity($event)
    {

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return $event . '_' . $type;
    }
}