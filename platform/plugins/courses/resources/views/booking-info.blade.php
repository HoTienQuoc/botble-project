<x-core::datagrid class="mb-4">
    <x-core::datagrid.item :title="__('Booking Number')">
        {{ $booking->booking_number }}
    </x-core::datagrid.item>

    <x-core::datagrid.item :title="__('Time')">
        {{ $booking->created_at->format('d M Y H:i') }}
    </x-core::datagrid.item>

    {{-- Customer Info --}}
    <x-core::datagrid.item :title="__('Full Name')">
        {{ $booking->customer->first_name }} {{ $booking->customer->last_name }}
    </x-core::datagrid.item>

    <x-core::datagrid.item :title="__('Email')">
        <a href="mailto:{{ $booking->customer->email }}">{{ $booking->customer->email }}</a>
    </x-core::datagrid.item>

    @if ($booking->customer->phone)
        <x-core::datagrid.item :title="__('Phone')">
            <a href="tel:{{ $booking->customer->phone }}">{{ $booking->customer->phone }}</a>
        </x-core::datagrid.item>
    @endif

    @if ($booking->customer->address)
        <x-core::datagrid.item :title="__('Address')">
            {{ $booking->customer->address }}
        </x-core::datagrid.item>
    @endif
</x-core::datagrid>

{{-- Course Detail --}}
@if ($booking->course)
    <x-core::datagrid class="mt-4">
        <x-core::datagrid.item :title="__('Course Name')">
            {{ $booking->course->name }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Price')">
            {{ format_price($booking->course->price) }}
        </x-core::datagrid.item>

        @if ($booking->course->duration)
            <x-core::datagrid.item :title="__('Duration')">
                {{ $booking->course->duration }}
            </x-core::datagrid.item>
        @endif

        @if ($booking->course->description)
            <x-core::datagrid.item :title="__('Description')">
                {{ Str::limit(strip_tags($booking->course->description), 120) }}
            </x-core::datagrid.item>
        @endif
    </x-core::datagrid>
@endif

{{-- Booking Amounts --}}
<x-core::datagrid class="mt-4">
    <x-core::datagrid.item :title="__('Sub Total')">
        {{ format_price($booking->sub_total) }}
    </x-core::datagrid.item>
    <x-core::datagrid.item :title="__('Discount Amount')">
        {{ format_price($booking->coupon_amount) }}
    </x-core::datagrid.item>
    <x-core::datagrid.item :title="__('Tax Amount')">
        {{ format_price($booking->tax_amount) }}
    </x-core::datagrid.item>
    <x-core::datagrid.item :title="__('Total Amount')">
        {{ format_price($booking->amount) }}
    </x-core::datagrid.item>

    <x-core::datagrid.item :title="__('Status')">
        {!! $booking->status !!}
    </x-core::datagrid.item>
</x-core::datagrid>

{{-- Course Instructor Info --}}
@if ($booking->course && $booking->course->instructor)
    <x-core::datagrid class="mt-4">
        <x-core::datagrid.item :title="__('Instructor Name')">
            {{ $booking->course->instructor->name }}
        </x-core::datagrid.item>

        <x-core::datagrid.item :title="__('Instructor Email')">
            <a href="mailto:{{ $booking->course->instructor->email }}">
                {{ $booking->course->instructor->email }}
            </a>
        </x-core::datagrid.item>

        @if ($booking->course->instructor->phone)
            <x-core::datagrid.item :title="__('Instructor Phone')">
                <a href="tel:{{ $booking->course->instructor->phone }}">
                    {{ $booking->course->instructor->phone }}
                </a>
            </x-core::datagrid.item>
        @endif
    </x-core::datagrid>
@endif
