@extends('layouts.app')

@section('content')
    <div class="container pt-20">
        <div class="row pl-20">
            <div class="col-3 p-5">
                @if ($user->profile)
                <img src="{{ asset($user->profile->getProfileImage()) }}" class="rounded-circle w-75">
                @endif
            </div>
            <div class="col-9 pt-5">
                <div class="d-flex align-items-center">
                    <h1>{{ $user->username }}</h1>

                    @can('update', $user->profile)
                        <a class="btn btn-secondary ml-3" href="/profile/{{$user->username}}/edit" role="button">
                            Edit Profile
                        </a>
                    @else
                        <follow-button user-id="{{ $user->username }}" follows="{{ $follows ?? "none" }}"></follow-button>
                    @endcan

                </div>
                <div class="d-flex">
                    <div class="pr-5"><strong> {{ $user->posts->count() }} </strong> posts</div>
                    <div class="pr-5"><strong>                     {{ $user->profile->followers->count() ?? ""}}
                        </strong> followers</div>
                    <div class="pr-5"><strong> {{ $user->following->count() }} </strong> following</div>
                </div>
                <div class="pt-4 font-weight-bold ">{{ $user->name }}</div>
                <div>
                    {!! nl2br(e($user->profile->bio ?? "none")) !!}
                </div>
                <div class="font-weight-bold">
                    <a href="{{ $user->profile->website ?? "none"}}" target="_blanc">
                        {{ $user->profile->website ?? "none"}}
                    </a>
                </div>

            </div>
        </div>

        <div class="row pt-4 border-top">

            @forelse ($user->posts as $post)
                <div class="col-4 col-md-4 mb-4 align-self-stretch">
                    <a href="/p/{{ $post->id }}">
                        <img class="img border" height="300" src="{{ asset("storage/$post->image") }}">
                    </a>
                </div>
            @empty
                <div class="col-12 d-flex justify-content-center text-muted">
                    <div class="card border-0 text-center bg-transparent" >
                        <img src="{{asset('img/noimage.png')}}" class="card-img-top" alt="...">
                        <div class="card-body ">
                            <h1>No Posts Yet</h1>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
@endsection
