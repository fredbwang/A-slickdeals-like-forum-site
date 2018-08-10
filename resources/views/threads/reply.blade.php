<div class="card">
    <div class="card-header">
        <span class="comment-meta">
            <a class="card-link" href="/profiles/{{ $reply->owner->name }}">
                {{$reply->owner->name}}
            </a> said
            {{$reply->created_at->diffForHumans()}} :
        </span>
        <div class="vote-section float-right">
            <form action="/replies/{{ $reply->id }}/vote-down" method="post">
                @csrf
                <button type="submit" class="btn btn-{{ $reply->isDownVote() ? 'secondary' : 'light' }} btn-sm">
                    <i class="fa fa-thumbs-down"></i> {{ $reply->down_votes_count }}
                </button>
            </form>
        </div>
        <div class="vote-section float-right">
            <form action="/replies/{{ $reply->id }}/vote-up" method="post">
                @csrf
                <button type="submit" class="btn btn-{{ $reply->isUpVote() ? 'primary' : 'light' }} btn-sm">
                    <i class="fa fa-thumbs-up"></i> {{ $reply->up_votes_count }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        {{$reply->body}}
    </div>
</div>