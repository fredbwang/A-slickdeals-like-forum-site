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
            
            @foreach ($threads as $key => $thread)
                <div class="card">
                    <div class="card-header">
                        <span class="h4">
                            <a href="{{$thread->path()}}">{{$thread->title}}</a>
                        </span>
                        <span class="pull-right">
                            <a href="{{ $thread->path() }}" class="badge badge-pill badge-secondary">
                                {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}
                            </a>             
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            {{$thread->body}}
                        </div>
                    </div>
                </div>
                <br>
            @endforeach
        </div>
    </div>
</div>
@endsection
