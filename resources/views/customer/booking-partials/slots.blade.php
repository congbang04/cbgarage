@if (isset($dayOfWeek) && !$dayActive)
    @switch($dayOfWeek)
        @case(0)
            <p class="text-warning">Ngày Chủ Nhật hàng tuần, cửa hàng nghỉ.</p>
        @break

        @case(1)
            <p class="text-warning">Ngày Thứ Hai hàng tuần, cửa hàng nghỉ.</p>
        @break

        @case(2)
            <p class="text-warning">Ngày Thứ Ba hàng tuần, cửa hàng nghỉ.</p>
        @break

        @case(3)
            <p class="text-warning">Ngày Thứ Tư hàng tuần, cửa hàng nghỉ.</p>
        @break

        @case(4)
            <p class="text-warning">Ngày Thứ Năm hàng tuần, cửa hàng nghỉ.</p>
        @break

        @case(5)
            <p class="text-warning">Ngày Thứ Sáu hàng tuần, cửa hàng nghỉ.</p>
        @break

        @case(6)
            <p class="text-warning">Ngày Thứ Bảy hàng tuần, cửa hàng nghỉ.</p>
        @break

        @default
            <p class="text-danger">Có lỗi. Ngày không xác định.</p>
    @endswitch
@elseif (empty($available_slots))
    <p class="text-warning">Thông báo! ngày này cửa hàng nghỉ.</p>
@else
    @foreach ($available_slots as $slot)
        @if ($slot['status'] === 'available')
            <button class="btn btn-outline-primary rounded-pill time-slot-btn" data-time="{{ $slot['time'] }}">
                {{ $slot['time'] }}
            </button>
        @elseif ($slot['status'] === 'locked')
            <button class="btn btn-warning rounded-pill time-slot-btn-locked locked-slot"
                data-expires="{{ $slot['expires_at'] }}" data-time="{{ $slot['time'] }}">
                {{ $slot['time'] }}
            </button>
        @elseif ($slot['status'] === 'unavailable')
            <button class="btn btn-danger rounded-pill time-slot-btn" disabled>
                {{ $slot['time'] }}
            </button>
        @elseif ($slot['status'] === 'lunch')
            <button class="btn btn-info rounded-pill time-slot-btn-locked btn-lunch" style="color: black;">
                <span class="slot-time">{{ $slot['time'] }}</span>
                <span class="slot-lunch" style="font-size: 0.9rem ">Nghỉ trưa</span>
            </button>
        @else
            <button class="btn btn-secondary rounded-pill time-slot-btn " disabled>
                {{ $slot['time'] }}
            </button>
        @endif
    @endforeach

@endif
