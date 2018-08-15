<reply :reply="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card">
        <div class="card-header">
            <span class="comment-meta">
                <a class="card-link" href="/profiles/{{ $reply->owner->name }}">
                    {{$reply->owner->name}}
                </a> said
                {{$reply->created_at->diffForHumans()}} :
            </span>
            <vote :reply="{{ $reply }}"></vote>
        </div>
        <div class="card-body">
            <div v-if="editting">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-sm btn-primary float-right" @click="update">Submit</button>
                <button class="btn btn-sm btn-light float-right mr-1" @click="cancel">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>
    
        @can ('update', $reply)
            <div class="card-footer">
                <button class="btn btn-sm btn-danger float-right" @click="destroy">Delete</button>
                <button class="btn btn-sm float-right mr-1" @click="editting=true">Edit</button>
            </div>
        @endcan
    </div>
</reply>