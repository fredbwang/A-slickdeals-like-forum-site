@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{isset($channel) ? $channel->name :"All Deals"}}</div>

                <div class="card-body">
                    @foreach ($threads as $key => $thread)
                        <div class="card-title">
                            <h4>
                                <a href="{{$thread->path()}}">{{$thread->title}}</a>
                            </h4>
                        </div>
                        <div class="card-subtitle mb-1">
                            <strong>
                                <a href="{{ $thread->path() }}">
                                    {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}
                                </a>             
                            </strong>
                        </div>
                        <div class="card-text">
                            {{$thread->body}}
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
