@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <h2 class="mb-4">Create Lease Agreement</h2>
  @include('leases.partials.form', [
    'lease' => null,
    'leaseFees' => [],
    'formAction' => route('leases.store'),
    'isEdit' => false,
    'properties' => $properties,
    'tenants' => $tenants
  ])
</div>
@endsection
