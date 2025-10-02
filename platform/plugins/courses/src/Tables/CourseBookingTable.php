<?php

namespace Botble\Courses\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Courses\Models\CourseBooking;
use Botble\Courses\Models\Course;
use Botble\Hotel\Tables\Formatters\PriceFormatter;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class CourseBookingTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseBooking::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('course-booking.create'))
            ->addActions([
                EditAction::make()->route('course-booking.edit'),
                DeleteAction::make()->route('course-booking.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->formatColumn('amount', PriceFormatter::class)
            ->editColumn('customer_id', function (CourseBooking $item) {
                return $item->customer && $item->customer->id
                    ? BaseHelper::clean($item->customer->first_name . ' ' . $item->customer->last_name)
                    : '&mdash;';
            })
            ->editColumn('course_id', function (CourseBooking $item) {
                return $item->course && $item->course->id
                    ? Html::link(
                        $item->course->url,
                        BaseHelper::clean($item->course->name),
                        ['target' => '_blank']
                    )
                    : '&mdash;';
            })
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query->whereHas('customer', function ($subQuery) use ($keyword) {
                        return $subQuery
                            ->where('first_name', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $keyword . '%')
                            ->orWhereRaw(
                                "CONCAT(first_name, ' ', last_name) LIKE ?",
                                ['%' . $keyword . '%']
                            );
                    });
                }

                return $query;
            });

        if (! is_plugin_active('payment')) {
            $data = $data->removeColumn('payment_status')->removeColumn('payment_id');
        } else {
            $data = $data
                ->editColumn('payment_status', function (CourseBooking $item) {
                    return $item->payment && $item->payment->status
                        ? BaseHelper::clean($item->payment->status->toHtml())
                        : '&mdash;';
                })
                ->editColumn('payment_id', function (CourseBooking $item) {
                    return $item->payment && $item->payment->payment_channel
                        ? BaseHelper::clean($item->payment->payment_channel->label())
                        : '&mdash;';
                });
        }

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'created_at',
                'status',
                'amount',
                'payment_id',
                'course_id',
                'customer_id',
            ])
            ->with(['customer', 'course']);

        if (is_plugin_active('payment')) {
            $query->with('payment');
        }

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        $columns = [
            IdColumn::make(),
            Column::make('customer_id')
                ->title(trans('plugins/hotel::booking.customer'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            Column::make('course_id')
                ->title(trans('plugins/courses::courses.course.name'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            Column::formatted('amount')
                ->title(trans('plugins/hotel::booking.amount'))
                ->alignLeft(),
            CreatedAtColumn::make(),
        ];

        if (is_plugin_active('payment')) {
            $columns = array_merge($columns, [
                Column::make('payment_id')
                    ->name('payment_id')
                    ->title(trans('plugins/hotel::booking.payment_method'))
                    ->alignLeft()
                    ->orderable(false)
                    ->searchable(false),
                Column::make('payment_status')
                    ->name('payment_id')
                    ->title(trans('plugins/hotel::booking.payment_status_label'))
                    ->orderable(false)
                    ->searchable(false),
            ]);
        }

        return array_merge($columns, [
            StatusColumn::make(),
        ]);
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('course-booking.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        $options = [
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => \Botble\Hotel\Enums\BookingStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', \Botble\Hotel\Enums\BookingStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];

        if (is_plugin_active('payment')) {
            $options['payment_status'] = [
                'title' => trans('plugins/courses::booking.payment_status_label'),
                'type' => 'select',
                'choices' => PaymentStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', PaymentStatusEnum::values()),
            ];
        }

        return $options;
    }

    public function applyFilterCondition(
        Builder|QueryBuilder|Relation $query,
        string $key,
        string $operator,
        ?string $value
    ): Relation|Builder|QueryBuilder {
        if ($key === 'payment_status') {
            return $query->whereHas('payment', function ($query) use ($value) {
                return $query->where('status', $value);
            });
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }

    public function saveBulkChangeItem(Model|CourseBooking $item, string $inputKey, ?string $inputValue): Model|bool
    {
        if ($inputKey === 'payment_status' && $item instanceof CourseBooking) {
            $item->payment()->update(['status' => $inputValue]);

            return $item;
        }

        return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    }
}
