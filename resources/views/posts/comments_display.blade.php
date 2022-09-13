@foreach($comments as $comment)
    <div class="display-comment pt-2" @if($comment->parent_id != null) style="margin-left:37px;" @endif>
        <strong>{{ $comment->user->name }}</strong>
        <p>{{ $comment->body }}</p>
        <a href="" id="reply"></a>
        <form method="post" action="{{ route('comments.store') }}">
            @csrf
            <div class="form-group">
                <input type="text" placeholder="comment something cool" style="border: 1px solid #fff; border-bottom: 1px solid #ccc" name="body" class="form-control" />
                <input type="hidden" name="post_id" value="{{ $post_id }}" />
                <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
            </div>
            <div class="form-group">
                <input type="submit" placeholder="reply" class="btn btn-light btn-shadow font-weight-bold btn-sm" value="Reply" />
            </div>
        </form>
        @include('posts.comments_display', ['comments' => $comment->replies])
    </div>
@endforeach
