<?php

namespace App\DataTables;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DiscountDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Discount> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                $user = auth()->user();

                $editButton = '';
                $deleteButton = '';

                // Check if the user has permissions for edit
                if ($user->hasRole('admin') || $user->hasPermission('discount-update')) {
                    $editButton = '<button class="btn btn-sm btn-warning edit-data" data-id="' . $data->id . '">
                                    <i class="fas fa-edit" style="font-size: 16px;"></i>
                                </button>';
                }

                // Check if the user has permissions for delete
                if ($user->hasRole('admin') || $user->hasPermission('discount-delete')) {
                    $deleteButton = '<button class="btn btn-sm btn-danger delete-data" data-id="' . $data->id . '">
                                    <i class="fas fa-trash" style="font-size: 16px;"></i>
                                </button>';
                }

                return $editButton . ' ' . $deleteButton;
            })
            ->addColumn('discount_type', function ($data) {
                $badgeClass = $data->type === 'percentage' ? 'bg-success' : 'bg-info';
                $typeText = $data->type === 'percentage' ? __('Percentage') : __('Fixed Amount');
                return '<span class="badge ' . $badgeClass . '">' . $typeText . '</span>';
            })
            ->addColumn('discount_value', function ($data) {
                if ($data->type === 'percentage') {
                    return $data->value . '%';
                } else {
                    return '$' . number_format($data->value, 2);
                }
            })
            ->addColumn('scope', function ($data) {
                $scopeType = $data->scope_type;
                $scopeNames = $data->scope_names;

                if ($scopeType === 'product') {
                    return '<span class="badge bg-primary" title="' . $scopeNames . '">' . __('Product') . '</span>';
                } elseif ($scopeType === 'category') {
                    return '<span class="badge bg-warning" title="' . $scopeNames . '">' . __('Category') . '</span>';
                } else {
                    return '<span class="badge bg-secondary">' . __('General') . '</span>';
                }
            })
            ->addColumn('status', function ($data) {
                $badgeClass = $data->active ? 'bg-success' : 'bg-danger';
                $statusText = $data->active ? __('Active') : __('Inactive');
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->addColumn('validity', function ($data) {
                if ($data->start_date && $data->end_date) {
                    $start = \Carbon\Carbon::parse($data->start_date)->format('Y-m-d');
                    $end = \Carbon\Carbon::parse($data->end_date)->format('Y-m-d');
                    return $start . ' - ' . $end;
                } elseif ($data->start_date) {
                    return __('From') . ' ' . \Carbon\Carbon::parse($data->start_date)->format('Y-m-d');
                } elseif ($data->end_date) {
                    return __('Until') . ' ' . \Carbon\Carbon::parse($data->end_date)->format('Y-m-d');
                } else {
                    return '<span class="text-muted">' . __('No limit') . '</span>';
                }
            })
            ->addColumn('usage', function ($data) {
                if ($data->max_uses > 0) {
                    return $data->uses . ' / ' . $data->max_uses;
                } else {
                    return $data->uses . ' / ∞';
                }
            })
            ->rawColumns(['action', 'discount_type', 'scope', 'status', 'validity', 'usage'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Discount>
     */
    public function query(Discount $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['products', 'categories'])
            ->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $languageFile = app()->getLocale() == 'ar' ? asset("admin/ar.json") : null;
        return $this->builder()
            ->setTableId('table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->language($languageFile ? ['url' => $languageFile] : [])
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('ID'))->addClass('text-center'),
            Column::make('code')->title(__('Code'))->addClass('text-center'),
            Column::make('discount_type')->title(__('Type'))->addClass('text-center'),
            Column::make('discount_value')->title(__('Value'))->addClass('text-center'),
            Column::make('scope')->title(__('Scope'))->addClass('text-center'),
            Column::make('status')->title(__('Status'))->addClass('text-center'),
            Column::make('validity')->title(__('Validity'))->addClass('text-center'),
            Column::make('usage')->title(__('Usage'))->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title(__('Action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Discount_' . date('YmdHis');
    }
}
