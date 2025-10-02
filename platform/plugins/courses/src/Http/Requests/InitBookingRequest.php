<?php

namespace Botble\Courses\Http\Requests;

use Botble\Hotel\Facades\HotelHelper;
use Botble\Hotel\Models\Room;
use Botble\Support\Http\Requests\Request;

class InitBookingRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'course_id' => ['required', 'exists:courses,id'],
        ];

        return $rules;
    }
}
