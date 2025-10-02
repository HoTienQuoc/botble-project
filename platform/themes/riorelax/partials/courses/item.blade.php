@php
$margin = $margin ?? false;
$isLoggedIn = auth('customer')->check() || auth()->check();
@endphp

<div @class(['single-services shadow-block mb-30', 'ser-m' => !$margin])>
<div class="services-thumb hover-zoomin wow fadeInUp animated">
    <a href="{{ $course->url }}">
        <img src="{{ RvMedia::getImageUrl($course->thumbnail, 'medium') }}" alt="{{ $course->name }}">
    </a>
</div>

<div class="services-content">
    <div class="day-book">
        <ul>
            <li>
                @if ($isLoggedIn)
                <a href="{{ $course->url }}"
                   class="book-button-custom d-inline-block text-center"
                   style="width:100%;"
                   data-animation="fadeInRight"
                   data-delay=".8s">
                    {{ __('Jetzt Buchen') }}
                </a>
                @else
                <a href="https://inspira-zentrum.net/de/nimm-kontakt-mit-uns-auf"
                   class="book-button-custom d-inline-block text-center"
                   style="width:100%;"
                   data-animation="fadeInRight"
                   data-delay=".8s">
                    {{ __('Anfragen') }}
                </a>
                @endif
            </li>
        </ul>
    </div>

    <h4><a href="{{ $course->url }}">{{ $course->name }}</a></h4>

    @if ($description = $course->description)
    <p class="room-item-custom-truncate" title="{{ $description }}">
        {!! BaseHelper::clean(Str::limit($description, 120)) !!}
    </p>
    @endif

    <ul class="course-meta mt-2">
        @if ($course->price)
        <li><strong>{{ __('Price:') }}</strong> {{ format_price($course->price) }}</li>
        @endif

        @if ($course->duration)
        <li><strong>{{ __('Duration:') }}</strong> {{ $course->duration }}</li>
        @endif

        @if ($course->start_date)
        <li><strong>{{ __('Start:') }}</strong> {{ BaseHelper::formatDate($course->start_date) }}</li>
        @endif

        @if ($course->category)
        <li><strong>{{ __('Category:') }}</strong> {{ $course->category->name }}</li>
        @endif
    </ul>
</div>
</div>
