@component('profiles.activities.activity')
    @slot('heading')
        <span>
            <a class="card-link" href="">
                {{$activity->owner->name}}
            </a>
            commented
            <a href="{{ $activity->subject->thread->path() }}">
                {{ $activity->subject->thread->title}}
            </a> 
        </span>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
    