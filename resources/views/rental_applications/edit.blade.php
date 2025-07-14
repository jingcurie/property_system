@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Edit Rental Application</h1>

    @include('rental_applications.partials.form', [
        'formAction' => route('rental_applications.update', $application->id),
        'isEdit' => true,
        'application' => $application,
        'properties' => $properties
    ])
@endsection
