@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span>
                        <a class="card-link" href="/profiles/{{ $thread->owner->name }}">
                            {{$thread->owner->name}}
                        </a> 
                        posted:
                        {{$thread->title}}
                    </span>
                    
                    <span class="float-right">
                        @can('delete', $thread)
                            <form action="{{ $thread->path() }}" method="POST">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button id="delete-btn" type="submit" class="btn btn-sm btn-secondary">Delete Post</button>
                            </form>
                        @endcan
                    </span>
                </div>
                <div class="card-body">
                    {{$thread->body}}
                </div>
            </div>
            <br>

            @foreach ($replies as $reply)
                @include('threads.reply')
                <br>
            @endforeach
            {{ $replies->links() }}
            
            @if (auth()->check())
                <form method="post" action="{{$thread->path('/replies')}}">
                    @csrf
                    <div class="form-group">
                        <label for="body">Reply:</label>
                        <textarea class="form-control" name="body" id="body" rows="10" placeholder="Say something about the deal?"></textarea>
                    </div>
                    <button class="btn btn-default" type="submit">Submit</button>
                </form>
            @else
                <div class="row justify-content-center">
                    <p><a href="{{route('login')}}">Sign in</a> to reply!</p>
                </div>
            @endif 
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">
                        This deal was posted: {{ $thread->created_at->diffForHumans() }} by 
                        <a href="">{{ $thread->owner->name }}</a>, and currently has 
                        {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
