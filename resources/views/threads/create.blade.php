@extends('layouts.app')

@section('content')
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
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose one...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'select' : ''}}>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea class="form-control" name="body" id="body" rows="10" required>{{old('body')}}</textarea>
                        </div>
                        <button class="form-group btn btn-default">Post</button>
                        @if (count($errors))
                        <div class="form-group">
                            <ul class="form-control alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
