@php
    $score = $activity->subject->score;
@endphp 

@if ($score != 0)
    @component('profiles.activities.activity')
        @slot('heading')
            <span>
                <i class="fa fa-thumbs-{{ $score == 1 ? "up" : "down" }} icon-activity pr-2"></i>
                <a class="card-link" href="">
                    {{$activity->owner->name}}
                </a>
                voted {{ $score == 1 ? "up" : "down" }} a
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
@endif
    