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
            <label class="time-slot-btn">
                <input type="radio" name="slot_time" data-time="{{ $slot['time'] }}">
                {{ $slot['time'] }}
            </label>
        @elseif ($slot['status'] === 'locked')
            <label class="time-slot-btn bg-warning">
                <input type="radio" name="slot_time" data-time="{{ $slot['time'] }}">
                {{ $slot['time'] }}
            </label>
        @elseif ($slot['status'] === 'unavailable')
            <label class="time-slot-btn bg-danger disabled">
                <input type="radio" name="slot_time" data-time="{{ $slot['time'] }}" disabled>
                {{ $slot['time'] }}
            </label>
        @elseif ($slot['status'] === 'lunch')
            <div class="time-slot-btn bg-info lunch d-hidden">Nghỉ trưa
            </div>
        @else
            <label class="time-slot-btn past">
                <input type="radio" name="slot_time" data-time="{{ $slot['time'] }}" disabled>
                {{ $slot['time'] }}
            </label>
        @endif
    @endforeach

@endif
