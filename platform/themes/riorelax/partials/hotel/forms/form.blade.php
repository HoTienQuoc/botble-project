@php
    Theme::asset()->container('footer')->add('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js', ['jquery']);
    Theme::asset()->container('footer')->add('bootstrap-js', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js', ['jquery', 'popper']);
    Theme::asset()->container('footer')->add('moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js');
    Theme::asset()->container('footer')->add('datetimepicker-js', 'https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js', ['bootstrap-js', 'moment-js']);
    Theme::asset()->container('footer')->add('datetimepicker-css', 'https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css');
@endphp

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('#StartDateTimePicker').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            sideBySide: true,
            minDate: moment(),
            icons: {
                time: 'fal fa-clock',
                date: 'fal fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });

        $('#EndDateTimePicker').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            sideBySide: true,
            minDate: moment().add(1, 'hours'),
            defaultDate: false,
            icons: {
                time: 'fal fa-clock',
                date: 'fal fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-times'
            }
        });
    });
</script>

<style>
    .input-group.date {
        position: relative;
    }

    .input-group.date .input-group-text {
        border: none;
        background: transparent;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        cursor: pointer;
        color: #666;
    }

    .input-group.date .form-control {
        padding-right: 35px; /* make space for icon */
    }

</style>

@if (is_plugin_active('hotel'))
    @php
        $minimumNumberOfGuests = HotelHelper::getMinimumNumberOfGuests();
        $maximumNumberOfGuests = HotelHelper::getMaximumNumberOfGuests();
        $startDate = request()->query('start_date', Carbon\Carbon::now()->format(HotelHelper::getDateFormat()));
        $endDate = request()->query('end_date', Carbon\Carbon::now()->addDay()->format(HotelHelper::getDateFormat()));
        $adults = request()->query('adults', $minimumNumberOfGuests);
    @endphp

    <form action="{{ $availableForBooking ? route('public.booking') : route('public.rooms') }}" method="{{ $availableForBooking ? 'POST' : 'GET' }}" class="contact-form mt-30 form-booking">
        @if ($availableForBooking)
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">
        @endif

        @switch($style)
            @case(2)
                <div class="row align-items-center">
                    @if (! empty($title))
                        <div class="col-lg-12">
                            <div class="section-title center-align mb-30">
                                <h2>{!! BaseHelper::clean($title) !!}</h2>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-2 col-md-6 mb-30">
                        <div class="contact-field p-relative c-name">
                            <label for="availability-form-start-date"><i class="fal fa-badge-check"></i>{{ __('Check In Date') }}</label>
                            <div class="input-group date" id="EndDateTimePicker" data-target-input="nearest">
                            <input
                                id="availability-form-start-date"
                                autocomplete="off"
                                type="text"
                                class="departure-date date-picker"
                                data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                                placeholder="{{ HotelHelper::getBookingFormDateFormat() }}"
                                data-locale="{{ App::getLocale() }}"
                                value="{{ BaseHelper::stringify($availableForBooking ? old('start_date', $startDate) : $startDate) }}"
                                name="start_date"
                            >
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-30">
                        <div class="contact-field p-relative c-name">
                            <label for="availability-form-end-date"><i class="fal fa-times-octagon"></i>{{ __('Check Out Date') }}</label>
                            <div class="input-group date" id="StartDateTimePicker" data-target-input="nearest">
                            <input
                                type="text"
                                id="availability-form-end-date"
                                autocomplete="off"
                                data-target="#StartDateTimePicker"
                                class="arrival-date date-picker"
                                data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                                placeholder="{{ HotelHelper::getBookingFormDateFormat() }}"
                                data-locale="{{ App::getLocale() }}"
                                value="{{ BaseHelper::clean($availableForBooking ? old('end_date', $endDate) : $endDate) }}"
                                name="end_date"
                            >
                                <span class="input-group-text" data-target="#StartDateTimePicker" data-toggle="datetimepicker">
        <i class="fal fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 mb-30">
                        <div class="contact-field p-relative c-name form-guests-and-rooms-wrapper">
                            <label for="adults"><i class="fal fa-users"></i>{{ __('Guests and Rooms') }}</label>
                            <button data-bb-toggle="toggle-guests-and-rooms" class="text-truncate" type="button" data-target="#toggle-guests-and-rooms">
                                <span data-bb-toggle="filter-adults-count" class="me-1">1</span> {{ __('Adult(s)') }} ,
                                <span data-bb-toggle="filter-children-count" class="ms-1 me-1">0</span> {{ __('Child(ren)') }},
                                <span data-bb-toggle="filter-rooms-count" class="me-1 ms-1">1</span> {{ __('Room(s)') }}
                            </button>

                            <div class="custom-dropdown dropdown-menu p-3" id="toggle-guests-and-rooms">
                                <div class="inputs-filed">
                                    <label for="adults">{{ __('Adults') }}</label>
                                    <div class="input-quantity">
                                        <button type="button" class="main-btn btn" data-bb-toggle="decrement-room">-</button>
                                        <input type="number" id="adults" name="adults" readonly value="1" min="{{ HotelHelper::getMinimumNumberOfGuests() }}" max="{{ HotelHelper::getMaximumNumberOfGuests() }}">
                                        <button type="button" class="main-btn btn" data-bb-toggle="increment-room">+</button>
                                    </div>
                                </div>
                                <div class="inputs-filed mt-30">
                                    <label for="children">{{ __('Children') }}</label>
                                    <div class="input-quantity">
                                        <button type="button" class="main-btn btn" data-bb-toggle="decrement-room">-</button>
                                        <input type="number" id="children" name="children" readonly value="0" min="0" max="{{ HotelHelper::getMaximumNumberOfGuests() }}">
                                        <button type="button" class="main-btn btn" data-bb-toggle="increment-room">+</button>
                                    </div>
                                </div>
                                <div class="inputs-filed mt-30">
                                    <label for="rooms">{{ __('Rooms') }}</label>
                                    <div class="input-quantity">
                                        <button type="button" class="main-btn btn" data-bb-toggle="decrement-room">-</button>
                                        <input type="number" id="rooms" name="rooms" readonly value="1" min="1" max="{{ 10 }}">
                                        <button type="button" class="main-btn btn" data-bb-toggle="increment-room">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="slider-btn">
                            <button type="submit" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">
                                {{ $availableForBooking ? __('Book Now') : __('Check Availability') }}
                            </button>
                        </div>
                    </div>
                </div>
                @break
            @default
                <div class="row booking-area">
                    @if (! empty($title))
                        <div class="col-lg-12">
                            <div class="section-title center-align mb-30">
                                <h2>{!! BaseHelper::clean($title) !!}</h2>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="contact-field p-relative c-name mb-20">
                            <label for="room-detail-booking-form-start-date"><i class="fal fa-badge-check"></i>{{ __('Check In Date') }}</label>
                            <div class="input-group date" id="StartDateTimePicker" data-target-input="nearest">
                            <input
                                type="text"
                                id="room-detail-booking-form-start-date"
                                class="departure-date datetimepicker-input"
                                autocomplete="off"
                                data-target="#StartDateTimePicker"
                                data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                                placeholder="{{ HotelHelper::getBookingFormDateFormat() }}"
                                data-locale="{{ App::getLocale() }}"
                                value="{{ BaseHelper::stringify($startDate ?: old('start_date', $startDate)) }}"
                                name="start_date"
                            >
                                <span class="input-group-text" data-target="#StartDateTimePicker" data-toggle="datetimepicker">
        <i class="fal fa-calendar-alt"></i>
    </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-field p-relative c-subject mb-20">
                            <label for="room-detail-booking-form-end-date"><i class="fal fa-times-octagon"></i>{{ __('Check Out Date') }}</label>
                            <div class="input-group date" id="EndDateTimePicker" data-target-input="nearest">
                            <input
                                type="text"
                                id="room-detail-booking-form-end-date"
                                class="arrival-date datetimepicker-input"
                                autocomplete="off"
                                data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                                placeholder="{{ HotelHelper::getBookingFormDateFormat() }}"
                                data-locale="{{ App::getLocale() }}"
                                value="{{ BaseHelper::stringify($endDate ?: old('end_date', $endDate)) }}"
                                name="end_date"
                                data-target="#EndDateTimePicker"
                            >
                                <span class="input-group-text" data-target="#EndDateTimePicker" data-toggle="datetimepicker">
        <i class="fal fa-calendar-alt"></i>
    </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-field p-relative c-subject input-group input-group-two left-icon mb-20">
                            <label for="adults"><i class="fal fa-users"></i>{{ __('Adults') }}</label>
                            <div class="input-quantity">
                                <button type="button" class="main-btn btn" data-bb-toggle="decrement-room">-</button>
                                <input type="number" id="adults" name="adults" readonly value="{{ BaseHelper::stringify(request()->integer('adults', 1)) }}" min="{{ HotelHelper::getMinimumNumberOfGuests() }}" max="{{ HotelHelper::getMaximumNumberOfGuests() }}">
                                <button type="button" class="main-btn btn" data-bb-toggle="increment-room">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="contact-field p-relative c-subject input-group input-group-two left-icon mb-20">
                            <label for="children"><i class="fal fa-child"></i>{{ __('Children') }}</label>
                            <div class="input-quantity">
                                <button type="button" class="main-btn btn" data-bb-toggle="decrement-room">-</button>
                                <input type="number" id="children" name="children" readonly value="{{  BaseHelper::stringify(request()->integer('children')) ?: 0 }}" min="0" max="{{ HotelHelper::getMaximumNumberOfGuests() }}">
                                <button type="button" class="main-btn btn" data-bb-toggle="increment-room">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="contact-field p-relative c-subject input-group input-group-two left-icon mb-20">
                            <label for="rooms"><i class="fal fa-hotel"></i>{{ __('Rooms') }}</label>
                            <div class="input-quantity">
                                <button type="button" class="main-btn btn" data-bb-toggle="decrement-room">-</button>
                                <input type="number" id="rooms" name="rooms" readonly value="{{ BaseHelper::stringify(request()->integer('rooms', 1)) }}" min="1" max="{{ 10 }}">
                                <button type="button" class="main-btn btn" data-bb-toggle="increment-room">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="slider-btn mt-15">
                            <button type="submit" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">
                                <span>{{ $availableForBooking ? __('Book Now') : __('Check Availability') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
        @endswitch
    </form>
@endif
