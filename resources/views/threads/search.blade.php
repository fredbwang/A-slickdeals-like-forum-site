@extends('layouts.app')

@section('content')
    <div class="container">
        <ais-index 
            app-id={{ config('scout.algolia.id') }}
            api-key={{ config('scout.algolia.key') }}
            index-name="threads"
            query="{{ request('keyword') }}">
            <div class="row">
                <div class="col-md-8">
                    <ais-search-box>
                        <div class="row">
                            <div class="input-group col-md-6 mb-2">
                                <ais-input :autofocus="true" placeholder="Find what you need" class="form-control"></ais-input>
                                <div class="input-group-append">
                                    <button class="input-group-text" id="search-bar">Search</button>
                                </div>
                            </div>
                        </div>
                    </ais-search-box>
                    <ais-results>
                        <template scope="{result}">
                            <li class="list-group-item">
                                <a :href="result.path">
                                    <ais-highlight :result="result" attribute-name="title">
                                    </ais-highlight>
                                </a>
                                <div class="body ml-3">
                                    <ais-highlight :result="result" attribute-name="body">
                                    </ais-highlight>
                                </div>
                            </li>
                        </template>
                    </ais-results>
                </div>
        
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-light bg-secondary">
                            <i class="fa fa-tags"></i>
                            Channel
                        </div>
                        <div class="card-body bg-default">
                            <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
                        </div>
                    </div>
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
        </ais-index>
    </div>
@endsection