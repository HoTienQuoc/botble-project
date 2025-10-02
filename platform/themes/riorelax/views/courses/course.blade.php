@php
    Theme::set('pageTitle', $course->name);
@endphp

<div class="course-detail-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            {{-- Left: Course Details --}}
            <div class="col-lg-8 col-md-12">

                {{-- Thumbnail --}}
                @if($course->thumbnail)
                    <div class="mb-4">
                        <img src="{{ RvMedia::getImageUrl($course->thumbnail, 'large') }}"
                             alt="{{ $course->name }}" class="img-fluid rounded">
                    </div>
                @endif

                {{-- Course Card --}}
                <div class="course-card shadow-sm p-4 mb-5 position-relative" style="border-radius: 10px; background: #fff;">

                    {{-- Price Top-Right --}}
                    @if($course->price)
                        <div class="position-absolute" style="top: 15px; right: 15px;">
                            <span class="badge bg-success py-2 px-3 fs-6">
                                {{ format_price($course->price) }}
                            </span>
                        </div>
                    @endif

                    {{-- Title --}}
                    <h2 class="mb-3">{{ $course->name }}</h2>

                    {{-- Meta --}}
                    <div class="course-meta mb-3 text-muted">
                        @if($course->duration)
                            <span>{{ __('Duration:') }} {{ $course->duration }}</span> |
                        @endif
                        @if($course->start_date)
                            <span>{{ __('Start:') }} {{ BaseHelper::formatDate($course->start_date) }}</span> |
                        @endif
                        @if($course->end_date)
                            <span>{{ __('End:') }} {{ BaseHelper::formatDate($course->end_date) }}</span> |
                        @endif
                        @if($course->category)
                            <span class="badge bg-secondary py-1 px-2">{{ $course->category->name }}</span>
                        @endif
                    </div>

                    <form action="{{ route('public.course.booking') }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <button type="submit" class="btn btn-primary btn-lg">{{ __('Book Now') }}</button>
                    </form>

                    {{-- Description --}}
                    <div class="course-description">
                        {!! BaseHelper::clean($course->description) !!}
                    </div>
                </div>

                @if(\Botble\Courses\Facades\CourseHelper::isReviewEnabled())
                    @include(Theme::getThemeNamespace('views.courses.partials.reviews'), ['model' => $course])
                @endif
            </div>

            {{-- Right: Instructor Info --}}
            <div class="col-lg-4 col-md-12">
                @if($course->instructor)
                    <div class="instructor-box shadow-sm p-4 mb-5" style="border-radius: 10px; background: #fff;">
                        <h3>{{ __('Instructor') }}</h3>
                        <div class="d-flex align-items-center mb-3">
                            @if($course->instructor->photo)
                                <img src="{{ RvMedia::getImageUrl($course->instructor->photo, 'thumb') }}"
                                     alt="{{ $course->instructor->name }}"
                                     class="rounded-circle me-3" width="80" height="80">
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $course->instructor->name ?? __('No Name') }}</h5>
                                @if($course->instructor->email)
                                    <p class="mb-1"><i class="fa fa-envelope"></i> {{ $course->instructor->email }}</p>
                                @endif
                                @if($course->instructor->phone)
                                    <p class="mb-1"><i class="fa fa-phone"></i> {{ $course->instructor->phone }}</p>
                                @endif
                            </div>
                        </div>
                        @if($course->instructor->bio)
                            <div>{!! BaseHelper::clean($course->instructor->bio) !!}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Related Courses --}}
        @if($relatedCourses->isNotEmpty())
            <div class="related-courses mt-5">
                <h3 class="mb-4">{{ __('Related Courses') }}</h3>
                <div class="row">
                    @foreach($relatedCourses as $related)
                        <div class="col-md-6 mb-3">
                            {!! Theme::partial('courses.item', ['course' => $related]) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
