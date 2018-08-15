@component('profiles.activities.activity')
    @slot('heading')
        <span>
            <i class="fa fa-comment icon-activity pr-2"></i>
            <a class="card-link" href="">
                {{$activity->owner->name}}
            </a>
            commented on
            <a href="{{ $activity->subject->path() }}">
                {{ $activity->subject->thread->title}}
            </a> 
        </span>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
    