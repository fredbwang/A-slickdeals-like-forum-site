<div class="card-header">
    <a class="card-link" href="">
        {{$reply->owner->name}}
    </a> said
    {{$reply->created_at->diffForHumans()}} :
</div>
<div class="card">
    <div class="card-body">
        {{$reply->body}}
    </div>
</div>