@extends('layouts.app')
@section('content')
<div class="container pt-1">
    <div class="row justify-content-center">
        {{-- Main section --}}
        <main class="main col-md-8 px-2 py-3">
            @forelse ($posts as $post)
                @php
                    $state=false;
                @endphp
                <div class="card mx-auto custom-card mb-5" id="prova" style="border: none">
                    <!-- Card Header -->
                    <div class="card-header d-flex justify-content-between align-items-center bg-white pl-3 pr-1 py-2">
                        <div class="d-flex align-items-center">
                            <a href="{{route('profile.index',$post->user->username) }}" style="width: 32px; height: 32px;">
                                <img src="{{ asset($post->user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                            </a>
                            <a href="{{route('profile.index', $post->user->username) }}" class="my-0 ml-3 text-dark text-decoration-none">
                                {{ $post->user->name }}
                            </a>
                        </div>
                        <div class="card-dots">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-link text-muted" data-toggle="modal" data-target="#post{{$post->id}}">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <!-- Dots Modal -->
                            <div class="modal fade" id="post{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <ul class="list-group">
                                            <a href="#"><li class="btn list-group-item">Unfollow</li></a>
                                            @can('delete', $post)
                                                <form action="{{route('post.destroy', $post->id)}}" method="POST">
                                                    @csrf
                                                    @method("DELETE")
                                                    <li class="btn list-group-item">
                                                        <button class="btn" type="submit">Delete</button>
                                                    </li>
                                                </form>
                                            @endcan
                                            <a href="{{route('post.show', $post->id)  }}"><li class="btn list-group-item">Go to post</li></a>
                                            <a href="#"><li class="btn list-group-item" data-dismiss="modal">Cancel</li></a>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card Image -->
                    <div class="tns" >
                        <div data-tns="true" data-tns-nav-position="bottom" data-tns-controls="false">
                            <!--begin::Item-->
                                @foreach($post->image as $value)
                                <div class="js-post" ondblclick="showLike(this, 'like_{{ $post->id }}')">
                                    <i class="fa fa-heart"></i>
                                    <img src="{{ URL::to($value) }}" class="card-img w-100 h-100" alt="" />
                                </div>
                                @endforeach
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body px-3 py-2">
                        <div class="d-flex flex-row">
                            <form method="POST" action="{{route('like.create', ['like'=>$post->id] ) }}">
                                @csrf
                                @if (true)
                                    <input id="inputid" name="update" type="hidden" value="1">
                                @else
                                    <input id="inputid" name="update" type="hidden" value="0">
                                @endif

                                @if($post->like->isEmpty())
                                    <button type="submit" class="btn pl-0">
                                        <i class="far fa-heart fa-2x"></i>
                                    </button>
                                @else
                                    @foreach($post->like as $likes)
                                        @if($likes->user_id==Auth::User()->id && $likes->State==true)
                                            @php
                                                $state=true;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if($state)
                                        <button type="submit" class="btn pl-0">
                                            <i class="fas fa-heart fa-2x" style="color:red"></i>
                                        </button>
                                    @else
                                        <button type="submit" class="btn pl-0">
                                            <i class="far fa-heart fa-2x"></i>
                                        </button>
                                    @endif
                                @endif
                                <a href="{{route('post.index', $post->id)  }}" style="padding-right: 15px" class="btn pl-0">
                                    <i class="far fa-comment fa-2x"></i>
                                </a>
                                <!-- Share Button trigger modal -->
                                <button type="button" class="btn pl-0 pt-0" data-toggle="modal" data-target="#sharebtn{{$post->id}}">
                                    <svg style="padding-top: 5px;margin-top: 10px;" aria-label="Share Post" class="_8-yf5 " fill="#262626" height="22" viewBox="0 0 48 48" width="21"><path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path></svg>
                                </button>
                            </form>
                            <!-- Share Modal -->
                            <div class="modal fade" id="sharebtn{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <ul class="list-group">
                                            <li class="list-group-item" style="position: absolute; left: -1000px; top: -1000px">
                                                <input type="text" value="{{route('post.show', $post->id)  }}" id="copy_{{ $post->id }}" />
                                            </li>
                                            <li class="btn list-group-item pb-2" data-dismiss="modal" onclick="copyToClipboard('copy_{{ $post->id }}')">Copy Link</li>
                                            <li class="btn list-group-item" data-dismiss="modal">Cancel</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-row">
                            <!-- Likes -->
                            @if (count($post->like->where('State',true)) > 0)
                                <h6 class="card-title">
                                    <strong>{{ count($post->like->where('State',true)) }} likes</strong>
                                </h6>
                            @endif
                            {{-- Post Caption --}}
                            <p class="card-text mb-1">
                                <a href="{{route('profile.index', $post->user->username) }}" class="my-0 text-dark text-decoration-none">
                                    <strong>{{ $post->user->name }}</strong>
                                </a>
                                {{ $post->caption }}
                            </p>
                            <!-- Comment -->
                                <div class="comments">
                                    @if (count($post->comments) > 0)
                                        <a href="{{route('post.show',$post->id)  }}" class="text-muted">View all {{count($post->comments)}} comments</a>
                                    @endif
                                    @foreach ($post->comments->sortByDesc("created_at")->take(2) as $comment)
                                        <p class="mb-1"><strong>{{ $comment->user->name }}</strong>  {{ $comment->body }}</p>
                                    @endforeach
                                </div>
                            <!-- Created At  -->
{{--                            <p class="card-text text-muted">{{ $post->created_at->diffForHumans() }}</p>--}}
                        </div>
                    </div>
                    <!-- Card Footer -->
                    <div class="card-footer p-0">
                        <!-- Add Comment -->
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-0  text-muted">
                                <div class="input-group is-invalid">
                                    <input type="hidden" name="post_id" value="{{$post->id}}">
                                    <textarea style="border: none; padding-top: 10px" class="form-control" id="body" name='body' rows="1" cols="1" placeholder="Add a comment..."></textarea>
                                    <div class="input-group-append">
                                        <button class="btn btn-md btn-outline-info" type="submit">Post</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="d-flex justify-content-center p-3 py-5 border bg-white">
                    <div class="card border-0 text-center">
                        <img src="{{asset('img/nopost.png')}}" class="card-img-top" alt="..." style="max-width: 330px">
                        <div class="card-body ">
                            <h3>No Post found</h3>
                            <p class="card-text text-muted">We couldn't find any post, Try to follow someone</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </main>
        {{-- Aside Section --}}
        <aside class="aside1 col-md-4 py-3" style="background-color: #fff ; margin-top: 15px">
            <div class="position-fixed">
                <!-- User Info -->
                <div class="d-flex align-items-center mb-3">
                    <a href="{{route('profile.index',Auth::user()->username)}}" style="width: 56px; height: 56px;">
                        @if(Auth::user()->profile)
                        <img src="{{ asset(Auth::user()->profile->getProfileImage()) }}" class="rounded-circle w-100">
                        @endif
                    </a>
                    <div class='d-flex flex-column pl-3'>
                        <a href="{{route('profile.index',Auth::user()->username)}}" class='h6 m-0 text-dark text-decoration-none' >
                            <strong>{{ auth()->user()->username }}</strong>
                        </a>
                        <small class="text-muted ">{{ auth()->user()->name }}</small>
                    </div>
                </div>
                <!-- Suggestions -->
                <div class='mb-4' style="width: 300px; padding-top: 20px">
                    <h5 class='text-secondary'>Suggestions For You</h5>
                        <!-- Suggestion Profiles-->
                        @foreach ($suggest_users as $sugg_user)
                            @if ($loop->iteration == 6)
                                @break
                            @endif
                            <div class='suggestions py-2'>
                                <div class="d-flex align-items-center ">
                                    <a href="{{route('profile.index', $sugg_user->username)}}" style="width: 32px; height: 32px;">
                                        <img src="{{ asset($sugg_user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                                    </a>
                                    <div class='d-flex flex-column pl-3'>
                                        <a href="{{route('profile.index', $sugg_user->username)}}" class='h6 m-0 text-dark text-decoration-none' >
                                            <strong>{{ $sugg_user->name}}</strong>
                                        </a>
                                        <small class="text-muted">New to Instagram </small>
                                    </div>
                                    <a href="#" class='ml-4 pb-2 text-decoration-none' style="color:#0095f6;">
                                        <b>Follow</b>
                                    </a>
                                </div>
                            </div>
                    @endforeach
                </div>
                <!-- CopyRight -->
                <div>
                    <span style='color: #a6b3be;'>© 2022 InstaClone from TuanAnhLe</span>
                </div>
            </div>
        </aside>

    </div>
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
        function showLike(e, id) {
            console.log("Like: ", id);
            var heart = e.firstChild;
            heart.classList.add('fade');
            setTimeout(() => {
                heart.classList.remove('fade');
            }, 2000);
        }
    </script>
@endsection

