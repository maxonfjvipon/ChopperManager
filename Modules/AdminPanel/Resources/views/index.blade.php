@extends('admin_panel::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('admin_panel.name') !!}
    </p>
@endsection
