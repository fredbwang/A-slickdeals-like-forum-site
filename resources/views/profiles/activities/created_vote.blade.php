@php
    $isVoteUp = $activity->subject->score == 1;
@endphp 

@component('profiles.activities.activity')
    @slot('heading')
        <span>
            <i class="fa fa-thumbs-{{ $isVoteUp ? "up" : "down" }} icon-activity pr-2"></i>
            <a class="card-link" href="">
                {{$activity->owner->name}}
            </a>
            voted {{ $isVoteUp ? "up" : "down" }} a
            <a href="{{ $activity->subject->voted->path() }}">
                {{-- {{ $activity->subject->voted->title }} --}}
                comment
            </a> 
        </span>
    @endslot

    @slot('body')
        {{ $activity->subject->voted->body }}
    @endslot
@endcomponent
    