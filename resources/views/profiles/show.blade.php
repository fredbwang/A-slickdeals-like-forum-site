@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container col-md-7">
            <div class="page-header">

                <avatar :user="{{ $profileUser }}" ></avatar>

            </div>
            <hr>
            @foreach ($activities as $date => $activitiesByDay)
                <h5 class="pt-3"> {{ $date }} </h5>
                <hr>
                @foreach ($activitiesByDay as $activity)
                    @if (view()->exists("profiles.activities.{$activity->type}"))
                        @include ("profiles.activities.{$activity->type}")
                    @endif
                @endforeach
            @endforeach 
        </div>
</div>
@endsection