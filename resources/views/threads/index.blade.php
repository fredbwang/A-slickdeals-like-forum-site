@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="page-header">
                @if (isset($channel))
                    <h3 class="strong">
                        {{ $channel->name }}
                    </h3>
                    <hr>
                @endif
            </div>
            
            @forelse ($threads as $key => $thread)
                <div class="card">
                    <div class="card-header">
                        <span class="h4">
                            <a class="card-link" href="{{$thread->path()}}">{{$thread->title}}</a>
                        </span>
                        <div>
                            By 
                            <a href="/profiles/{{ $thread->owner->name }}" class="card-link">{{ $thread->owner->name }}</a>
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
                    <div class="card-footer">
                        <i class="fa fa-eye"></i> {{ $thread->visitsCount }}
                    </div>
                </div>
                <br>
            @empty
                <p>No results</p>
            @endforelse

            {{ $threads->render() }}
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-light bg-secondary">
                    <i class="fa fa-fire"></i>
                    Trending
                </div>
                <div class="card-body bg-default">
                    @forelse($trending as $thread)
                        <li class="card-title text-secondary">
                            <a class="card-link text-secondary" href="{{ $thread->path }}">
                                {{ $thread->title }}
                            </a>
                        </li>
                    @empty
                        @include('threads.easter-egg')
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
