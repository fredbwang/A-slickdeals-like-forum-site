@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{$thread->owner->name}} posted:
                    {{$thread->title}}
                </div>
                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>
            <br>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($thread->replies as $reply)
                @include('threads.reply')
                <br>
            @endforeach
        </div>
    </div>

    @if (auth()->check())
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <form method="post" action="{{$thread->path('/replies')}}">
                    @csrf
                    <div class="form-group">
                        <label for="body">Body:</label>
                        <textarea class="form-control" name="body" id="body" rows="10" placeholder="Wanna join this discussion?"></textarea>
                    </div>
                    <button class="btn btn-default" type="submit">Submit</button>
                </form>
            </div>
        </div>
    @else 
    <div class="row justify-content-center">
        <p><a href="{{route('login')}}">Sign in</a> to reply!</p>
    </div>
    @endif
</div>
@endsection
