@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Post  --}}
    <div class="post">
        <div class="row mt-3">
            <div class="card w-100">
                <div class="row no-gutters" style="height: 598px;">
                    <div class="tns col-md-8 h-100" >
                        <div data-tns="true" data-tns-nav-position="bottom" data-tns-controls="false">
                            <div class="">
                                    <img src="{{ URL::to($post->firstImage) }}" class="card-img w-100 h-100" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 h-100">
                        <div class="d-flex flex-column h-100">
                            <!-- Card Header -->
                            <div class="card-header" style="min-height: 40px">
                                <div class="d-flex align-items-center">
                                    <a href="{{route('profile.index',$post->user->username)}}" style="width: 32px; height: 32px;">
                                        <img src="{{ asset($post->user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                                    </a>
                                    <a href="{{route('profile.index', $post->user->username) }}" class="my-0 ml-3 text-dark text-decoration-none">
                                        <strong> {{ $post->user->name }}</strong>
                                    </a>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body" style="overflow-y: auto; overflow-x: hidden;">
                                <!-- Post Caption  -->
                                <div class="row pl-1 pb-1" style="border-bottom: 1px solid #fafafa">
                                    <div class="col-2">
                                        <a href="{{route('profile.index', $post->user->username) }}">
                                            <img src="{{ asset($post->user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                                        </a>
                                    </div>
                                    <div class="col-10 pl-0">
                                        <p class="m-0 text-dark">
                                            <a href="/profile/{{$post->user->username}}" class="my-0 text-dark text-decoration-none">
                                                <strong> {{ $post->user->name }}</strong>
                                            </a>
                                            {{ $post->caption }}
                                        </p>
                                    </div>
                                </div>
                                {{-- comments --}}
                                @include('posts.comments_display', ['comments' => $post->comments, 'post_id' => $post->id])
                            </div>
                            <!-- Card Footer -->
                            <div class="card-footer align-self-end w-100 p-0 border-top-0">
                                <!-- Post State -->
                                <div class="py-2 px-3 ">
                                    <div class="d-flex flex-row">
                                        {{-- Like Btn --}}
                                        <button type="submit" class="btn pl-0">
                                            <i class="far fa-heart fa-2x"></i>
                                        </button>
                                        {{-- Comment Btn --}}
                                        <button name="msg" value="0" type="button" class="btn pl-0">
                                            <i class="far fa-comment fa-2x"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="sharebtn{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <ul class="list-group">
                                                        <li class="list-group-item" style="position: absolute; left: -1000px; top: -1000px">
                                                            <input type="text" value="http://localhost:8000/p/{{ $post->id }}" id="copy_{{ $post->id }}" />
                                                        </li>
                                                        <li class="btn list-group-item" data-dismiss="modal" onclick="copyToClipboard('copy_{{ $post->id }}')">Copy Link</li>
                                                        <li class="btn list-group-item" data-dismiss="modal">Cancel</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Post Likes --}}
                                    @if ($post->likes > 0)
                                        <p class="m-0"><strong>{{ $post->likes }} likes</strong></p>
                                    @endif
                                    {{-- Post Date --}}
{{--                                        <p class="m-0"><small class="text-muted">{{ strtoupper($post->created_at->diffForHumans()) }}</small></p>--}}
                                </div>

                                <!-- Add Comment -->
                                <form action="{{ route('comments.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-0 text-muted" >
                                        <div class="input-group is-invalid">
                                            <input type="hidden" name="post_id" value="{{$post->id}}">
{{--                                                <input type="hidden" name="body" value="show">--}}
                                            <textarea class="form-control ml-2 py-2 px-3" id="body" style="border: none" name='body' rows="1" placeholder="Add a comment..."></textarea>
                                            <div class="input-group-append">
                                                <button class="btn btn-md btn-outline-info" type="submit">Post</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- More Posts --}}
    @if ($posts->count() > 0)
        <hr class="my-5">
        <div class="more">
            <h6 class="text-muted">More posts from
                <a href="{{route('profile.index', $post->user->username) }}" class="text-dark text-decoration-none">
                    <strong> {{ $post->user->name }}</strong>
                </a>
            </h6>
            <div class="row">
                @foreach ($posts as $post)
                    @php
                        $post->image = explode('|', $post->image);
                    @endphp
                    @foreach($post->image as $value)
                    <div class="col-4 col-md-4 mb-2  align-self-stretch">
                        <a href="{{route('post.show',$post->id)  }}">
                            <img class="img rounded" height="300" src="{{ URL::to($value) }}">
                        </a>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@section('exscript')
    <script>
        function copyToClipboard(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
        }
    </script>
@endsection
