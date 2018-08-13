@component('profiles.activities.activity')
    @slot('heading')
        <span>
            <a class="card-link" href="">
                {{$activity->owner->name}}
            </a>
            posted 
            <a href="{{ $activity->subject->path() }}">
                {{ $activity->subject->title}}
            </a> 
        </span>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
        
