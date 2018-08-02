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
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <textarea class="form-control" name="body" id="body" rows="10"></textarea>
                        </div>
                        <button class="btn btn-default">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
