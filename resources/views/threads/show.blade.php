@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
<thread inline-template :data-thread="{{ $thread }}">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span>
                            <img src="{{ $thread->owner->avatar_path }}" alt="" width="24" height="24" class="user-avatar-sm mr-2">
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
                    <div class="card-footer">
                        {{ $thread->visitsCount }} Visits
                    </div>
                </div>
                <br>

                <replies @removed="repliesCount--" @added="repliesCount++">
                </replies>
                
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">
                            This deal was posted: {{ $thread->created_at->diffForHumans() }} by 
                            <a href="">{{ $thread->owner->name }}</a>, and currently has 
                            <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            <div v-cloak v-if="locked">
                                <hr>
                                <i class="fa fa-lock mr-1"></i>The thread is currently locked.
                            </div>
                        </p>

                        <subscribe-btn v-cloak v-if="signedIn" :initial-active="{{ json_encode($thread->isSubscribedBy) }}"></subscribe-btn>
                        
                        <span v-if="authorize('isAdmin')">
                            <button v-cloak v-if="locked" @click="unlock" class="btn btn-default">Unlock</button>
                            <button v-cloak v-else @click="lock" class="btn btn-default">Lock</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread>
@endsection
