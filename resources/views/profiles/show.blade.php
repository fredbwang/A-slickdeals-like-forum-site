@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container col-md-7">
            <div class="page-header">
                <h3>
                    {{ $profileUser->name }}
                    <small> A member since {{ $profileUser->created_at->diffForHumans() }}</small>
                </h3>
            </div>
            <hr>
            @foreach ($activities as $date => $activitiesByDay)
                <h5 class="pt-3"> {{ $date }} </h5>
                <hr>
                @foreach ($activitiesByDay as $activity)
                    @include ("profiles.activities.{$activity->type}")
                @endforeach
            @endforeach 
        </div>
</div>
@endsection