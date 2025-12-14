@php
  $map = [
    'pending' => ['Chờ xử lý','warning'],
    'processing' => ['Đang xử lý','info'],
    'completed' => ['Hoàn tất','success'],
    'cancelled' => ['Đã hủy','danger']
  ];
@endphp

<span class="badge bg-{{ $map[$status][1] ?? 'secondary' }}">
  {{ $map[$status][0] ?? 'Không rõ' }}
</span>
