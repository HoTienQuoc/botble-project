<?php

namespace Botble\Courses\Models;

use Botble\Base\Models\BaseModel;
use Botble\Hotel\Enums\BookingStatusEnum;
use Botble\Hotel\Models\Customer;
use Botble\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseBooking extends BaseModel
{
    protected $table = 'course_bookings';

    protected $fillable = [
        'course_id',
        'customer_id',
        'transaction_id',
        'booking_number',
        'amount',
        'sub_total',
        'coupon_amount',
        'coupon_code',
        'tax_amount',
        'status',
        'additional_info',
    ];

    protected $casts = [
        'status' => BookingStatusEnum::class,
        'additional_info' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public static function generateUniqueBookingNumber(): string
    {
        $nextInsertId = static::query()->max('id') + 1;

        do {
            $code = static::getBookingNumber($nextInsertId);
            $nextInsertId++;
        } while (static::query()->where('booking_number', $code)->exists());

        return $code;
    }

    public static function getBookingNumber(int|string $id): string
    {
        $prefix = setting('course_booking_number_prefix') ? setting('course_booking_number_prefix') . '-' : '';
        $suffix = setting('course_booking_number_suffix') ? '-' . setting('course_booking_number_suffix') : '';

        return sprintf(
            '#%s%d%s',
            $prefix,
            (int) config('plugins.courses.courses.default_number_start_number', 1000) + $id,
            $suffix
        );
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id')->withDefault();
    }
}
