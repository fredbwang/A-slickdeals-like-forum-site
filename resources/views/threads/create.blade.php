@extends('layouts.app')

@section('head')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
{{ $errors }}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Post a Deal</div>

                <div class="card-body">
                    <form method="post" action="/threads">
                        @csrf

                        <div class="form-group">
                            <label for="channel_id">Channel:</label>
                            <select name="channel_id" id="channel_id" required
                                    class="form-control {{ $errors->has('channel') ? 'is-invalid' : (count($errors) ? 'is-valid' : '') }}">
                                <option value="">Choose one...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected' : ''}}>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
                            @include('threads.form-feedback', ['object' => 'channel'])
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" name="title" id="title" value="{{old('title')}}" required
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : (count($errors) ? 'is-valid' : '') }}">
                            @include('threads.form-feedback', ['object' => 'title'])
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea class="form-control {{ $errors->has('body') ? 'is-invalid' : (count($errors) ? 'is-valid' : '') }}"
                                    name="body" id="body" rows="10" required> {{ old('body') }}
                            </textarea>
                            @include('threads.form-feedback', ['object' => 'body'])
                        </div>

                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6Leow28UAAAAAFIlgCQyldMJD4poQGmWGUH0kZxa"></div>
                            @if ($errors->has('g-recaptcha-response')) 
                                <p><small class="text-danger">{{ $errors->first('g-recaptcha-response') }}</small></p>
                            @endif
                        </div>
                        
                        <button class="form-group btn btn-default">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
