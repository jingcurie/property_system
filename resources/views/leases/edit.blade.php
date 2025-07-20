@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <h2 class="mb-4">Edit Lease Agreement</h2>
  @include('leases.form', [
    'lease' => $lease,
    'leaseFees' => $leaseFees ?? [],
    'formAction' => route('leases.update', $lease->id),
    'isEdit' => true,
    'properties' => $properties,
    'tenants' => $tenants
  ])
</div>
@endsection
