@props(['status'])

@php
    $map = [
        'Available' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'check-circle-fill'],
        'Leased' => ['bg' => '#e5e7eb', 'text' => '#374151', 'icon' => 'clock-fill'],
        'Under Maintenance' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'wrench-adjustable-circle-fill'],
    ];
    $s = $map[$status] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280', 'icon' => 'question-circle'];
@endphp

<span class="badge d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill"
      style="background-color: {{ $s['bg'] }}; color: {{ $s['text'] }};">
  <i class="bi bi-{{ $s['icon'] }}"></i> {{ $status }}
</span>
