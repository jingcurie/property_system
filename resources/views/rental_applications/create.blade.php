@extends('layouts.app')

@section('content')
    <h1 class="mb-4">New Rental Application</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('rental_applications.partials.form', [
        'formAction' => route('rental_applications.store'),
        'isEdit' => false,
        'application' => null,
        'properties' => $properties,
    ])
@endsection