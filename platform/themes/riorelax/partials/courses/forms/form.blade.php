@php
    use Botble\Courses\Models\CourseCategory;
    use Botble\Courses\Models\Instructor;

    $categories = CourseCategory::where('status', 'published')->get();
    $instructors = Instructor::where('status', 'published')->get();

    $selectedCategory = request()->get('category_id');
    $selectedInstructor = request()->get('instructor_id');
    $minPrice = request()->get('min_price');
    $maxPrice = request()->get('max_price');
    $startDate = request()->get('start_date');
@endphp

<form action="{{ route('public.courses') }}" method="GET" class="contact-form mt-30 course-filter-form">

    <div class="contact-field p-relative c-name mb-20">
        <label><i class="fal fa-layer-group"></i> {{ __('Category') }}</label>
        <select name="category_id" class="form-control">
            <option value="">{{ __('All Categories') }}</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected($selectedCategory == $cat->id)>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="contact-field p-relative c-name mb-20">
        <label><i class="fal fa-chalkboard-teacher"></i> {{ __('Instructor') }}</label>
        <select name="instructor_id" class="form-control">
            <option value="">{{ __('All Instructors') }}</option>
            @foreach ($instructors as $inst)
                <option value="{{ $inst->id }}" @selected($selectedInstructor == $inst->id)>
                    {{ $inst->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="contact-field p-relative c-name mb-20">
        <label><i class="fal fa-calendar-alt"></i> {{ __('Start Date After') }}</label>
        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
    </div>

    <div class="contact-field p-relative c-name mb-20">
        <label><i class="fal fa-dollar-sign"></i> {{ __('Price Range') }}</label>
        <div class="d-flex gap-2">
            <input type="number" name="min_price" class="form-control" placeholder="{{ __('Min') }}" value="{{ $minPrice }}">
            <input type="number" name="max_price" class="form-control" placeholder="{{ __('Max') }}" value="{{ $maxPrice }}">
        </div>
    </div>

    <div class="slider-btn mt-15">
        <button type="submit" class="btn ss-btn w-100" data-animation="fadeInRight" data-delay=".8s">
            {{ __('Filter Courses') }}
        </button>
    </div>
</form>
