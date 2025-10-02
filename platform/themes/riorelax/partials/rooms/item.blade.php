@php
    $margin = $margin ?? false;
    $isLoggedIn = auth('customer')->check() || auth()->check();
@endphp

<div @class(['single-services shadow-block mb-30', 'ser-m' => !$margin])>
    <div class="services-thumb hover-zoomin wow fadeInUp animated">
        @if ($images = $room->images)
            <a href="{{ $room->url }}?start_date={{ BaseHelper::stringify(request()->query('start_date', $startDate)) }}&end_date={{ BaseHelper::stringify(request()->query('end_date', $endDate)) }}&adults={{ BaseHelper::stringify(request()->query('adults', HotelHelper::getMinimumNumberOfGuests())) }}&children={{ BaseHelper::stringify(request()->query('children', 0)) }}">
                <img src="{{ RvMedia::getImageUrl(Arr::first($images), 'medium') }}" alt="{{ $room->name }}">
            </a>
        @endif
    </div>

    <div class="services-content">
        @if (HotelHelper::isBookingEnabled())
            <div class="day-book">
                <ul>
                    <li>
                        @if ($isLoggedIn)
                            {{-- "JETZT BUCHEN" Button zur Detailseite --}}
                            <a
                                href="{{ $room->url }}?start_date={{ BaseHelper::stringify(request()->query('start_date', $startDate)) }}&end_date={{ BaseHelper::stringify(request()->query('end_date', $endDate)) }}&adults={{ BaseHelper::stringify(request()->query('adults', HotelHelper::getMinimumNumberOfGuests())) }}&children={{ BaseHelper::stringify(request()->query('children', 0)) }}"
                                class="book-button-custom d-inline-block text-center"
                                style="width:100%;"
                                data-animation="fadeInRight"
                                data-delay=".8s"
                            >
                                {{ __('Jetzt Buchen') }}
                            </a>
                        @else
                            {{-- "Anfragen" Button für nicht eingeloggte User --}}
                            <a
                                href="https://inspira-zentrum.net/de/nimm-kontakt-mit-uns-auf"
                                class="book-button-custom d-inline-block text-center"
                                style="width:100%;"
                                data-animation="fadeInRight"
                                data-delay=".8s"
                            >
                                {{ __('Anfragen') }}
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        @endif

        <h4><a href="{{ $room->url }}">{{ $room->name }}</a></h4>

        @if ($description = $room->description)
            <p class="room-item-custom-truncate" title="{{ $description }}">{!! BaseHelper::clean($description) !!}</p>
        @endif

        @if ($room->amenities->isNotEmpty())
            <div class="icon">
                <ul class="d-flex justify-content-evenly">
                    @foreach ($room->amenities->take(6) as $amenity)
                        @if ($image = $amenity->getMetaData('icon_image', true))
                            <li>
                                <img src="{{ RvMedia::getImageUrl($image) }}" alt="{{ $amenity->name }}">
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
