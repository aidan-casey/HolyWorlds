@extends ('master', ['breadcrumb_other' => trans('posts.edit')])

@section('title', 'Editing post')

@section ('content')
    @if ($post->parent)
        <h3>{{ trans('general.response_to', ['item' => $post->parent->authorName]) }}...</h3>

        @include ('post.partials.excerpt', ['post' => $post->parent])
    @endif

    <form method="POST" action="{{ route('forum.post.update', $post) }}">
        {!! csrf_field() !!}
        {!! method_field('patch') !!}

        <div class="form-group">
            <textarea name="content" class="form-control">{{ !is_null(old('content')) ? old('content') : $post->content }}</textarea>
        </div>

        <button type="submit" class="waves-effect waves-light btn-large pull-right">{{ trans('general.proceed') }}</button>
        <a href="{{ URL::previous() }}" class="waves-effect waves-light btn-large blue-grey lighten-2">{{ trans('general.cancel') }}</a>
    </form>
@stop

@section('after_content')
@if (!$post->isFirst)
    @can ('delete', $post)
        <hr>
        <form action="{{ route('forum.post.update', $post) }}" method="POST" data-actions-form>
            {!! csrf_field() !!}
            {!! method_field('delete') !!}

            @include ('post.partials.actions')
        </form>
    @endcan
@endif
@stop
