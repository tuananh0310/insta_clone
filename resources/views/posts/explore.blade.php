@extends('layouts.app')
@section('content')
    <div class="container pt-7">
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-4 col-md-4 mb-4">
                    <div class="card">
                        <!-- Post Image -->
                        <a href="{{route('post.show',$post->id)  }}" style='height: 320px; width: auto'>
                            <img src="{{ $post->firstImage }}" class="card-img-top h-100 w-100" style="object-fit: cover" alt="..." >
                        </a>
                        <!-- User Info -->
                        <div class="card-body py-2 px-2">
                            <div class="d-flex align-items-start">
                                <a href="{{route('profile.index', $post->user->username) }}" style="width: 32px; height: 32px;">
                                    <img src="{{ asset($post->user->profile->getProfileImage()) }}" style="object-fit: cover" class="rounded-circle w-100">
                                </a>
                                <div class='flex-grow-1 d-flex flex-column pl-2'>
                                    <a href="{{route('profile.index',$post->user->username) }}" class='h6 m-0 text-dark text-decoration-none' >
                                        <strong>{{ $post->user->username }}</strong>
                                    </a>
                                    <small class="text-muted">{{ $post->user->name }}</small>
                                </div>
                                <div class="align-self-center">
                                    <strong>{{  count($post->like->where('State',true)) }} likes</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
@endsection
