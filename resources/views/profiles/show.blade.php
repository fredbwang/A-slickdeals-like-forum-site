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
            @foreach ($threads as $thread)
                <div class="card">
                    <div class="card-header">
                        <span>
                            <a class="card-link" href="">
                                {{$thread->owner->name}}
                            </a>
                            posted:
                             <a href="{{ $thread->path() }}">
                                {{ $thread->title}}
                            </a> 
                        </span>
                        <span class="float-right">
                            {{ $thread->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="card-body">
                        {{$thread->body}}
                    </div>
                </div>
                <br>
            @endforeach 
            {{ $threads->links() }}
        </div>
</div>
@endsection