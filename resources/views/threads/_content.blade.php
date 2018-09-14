<div class="card" v-if="editting" v-cloak>
    <div class="card-header">
        <input class="form-control" type="text" v-model="title">
    </div>
    <div class="card-body">
        <forum-editor v-model="body" name="body" :value="body"></forum-editor>
        {{-- <textarea class="form-control" name="" id="" rows="10" v-model="body"></textarea> --}}
    </div>
    <div class="card-footer">
        @can('delete', $thread)
            <form action="{{ $thread->path() }}" method="POST">
                @csrf
                {{ method_field('DELETE') }}
                <button id="delete-btn" type="submit" class="btn btn-sm btn-secondary float-right ml-2">Delete Deal</button>
            </form>
        @endcan

        @can('update', $thread)
            <button class="btn btn-primary btn-sm mr-2" @click="submit">Submit</button>
            
            <button class="btn btn-default btn-sm" @click="cancel">
                <i class="fa fa-undo mr-1"></i>Cancel  
            </button>
        @endcan
    </div>
</div>

<div class="card" v-else>
    <div class="card-header">
        <span>
            <img src="{{ $thread->owner->avatar_path }}" alt="" width="24" height="24" class="user-avatar-sm mr-2">
            <a class="card-link" href="/profiles/{{ $thread->owner->name }}">
                {{$thread->owner->name}}
            </a> 
            posted:
            <span v-text="title"></span>
        </span>

        <span class="card-text float-right">
            {{ $thread->visitsCount }} Visits
        </span>
    </div>

    <div class="card-body" v-html="body"></div>
    
    <div class="card-footer">
        @can('delete', $thread)
            <form action="{{ $thread->path() }}" method="POST">
                @csrf
                {{ method_field('DELETE') }}
                <button id="delete-btn" type="submit" class="btn btn-sm btn-secondary float-right ml-2">Delete Deal</button>
            </form>
        @endcan

        @can('update', $thread)
            <button class="btn btn-default btn-sm ml-2 float-right" @click="edit">
                <i class="fa fa-edit"></i>Edit
            </button>
        @endcan
    </div>
</div>