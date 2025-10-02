@extends(HotelHelper::viewPath('customers.master'))

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="text-center mb-20">{{ SeoHelper::getTitle() }}</h1>
        </div>
        <div class="panel-body">
            <div class="section-content">
                <div class="table-responsive mb-20">
                    <table class="table table-striped custom-booking-table">
                        <thead class="text-center">
                        <tr>
                            <th>{{ __('Course') }}</th>
                            <th>{{ __('Instructor') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Booking Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @if ($courseBookings->count() > 0)
                            @foreach ($courseBookings as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ $booking->course->url ?? '#' }}" target="_blank">
                                            {{ $booking->course->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->course->instructor->name ?? '-' }}</td>
                                    <td>{{ $booking->course->category->name ?? '-' }}</td>
                                    <td>{{ format_price($booking->amount) }}</td>
                                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                                    <td>{!! $booking->status->label() !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">{{ __('No course bookings!') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                {!! $courseBookings->links(Theme::getThemeNamespace('partials.pagination')) !!}
            </div>
        </div>
    </div>
@stop
