@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="page-header">
                <h3 class="strong">
                    {{isset($channel) ? $channel->name :"All Deals"}}
                </h3>
            </div>

            <hr>
            
            @forelse ($threads as $key => $thread)
                <div class="card">
                    <div class="card-header">
                        <span class="h4">
                            <a class="card-link" href="{{$thread->path()}}">{{$thread->title}}</a>
                        </span>
                        <div>
                            By 
                            <a href="/profiles/{{ $thread->owner->username }}" class="card-link">{{ $thread->owner->name }}</a>
                            <span class="float-right">
                                <a href="{{ $thread->path() }}" class="badge badge-pill badge-secondary">
                                    {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}
                                </a>             
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            {{$thread->body}}
                        </div>
                    </div>
                </div>
                <br>
            @empty
                <p>No results</p>
            @endforelse

            {{ $threads->render() }}
        </div>
    </div>
</div>
@endsection
