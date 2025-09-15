<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                $user = auth()->user();

                $editButton = '';
                $deleteButton = '';

                // Check if the user has permissions for edit
                if ($user->hasRole('admin') || $user->hasPermission('product-update')) {
                    $editButton = '<button class="btn btn-sm btn-warning edit-data" data-id="' . $data->id . '">
                                    <i class="fas fa-edit" style="font-size: 16px;"></i>
                                </button>';
                }

                // Check if the user has permissions for delete
                if ($user->hasRole('admin') || $user->hasPermission('product-delete')) {
                    $deleteButton = '<button class="btn btn-sm btn-danger delete-data" data-id="' . $data->id . '">
                                    <i class="fas fa-trash" style="font-size: 16px;"></i>
                                </button>';
                }

                return $editButton . ' ' . $deleteButton;
            })
            ->addColumn('image', function ($data) {
                if ($data->productImages && $data->productImages->count() > 0) {
                    $firstImage = $data->productImages->first();
                    return '<img src="' . $firstImage->image . '" alt="' . $data->name_en . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('category', function ($data) {
                return $data->category ? $data->category->name_en : '<span class="text-muted">No Category</span>';
            })
            ->addColumn('store', function ($data) {
                return $data->store ? $data->store->name : '<span class="text-muted">No Store</span>';
            })
            ->addColumn('price', function ($data) {
                $price = number_format($data->price, 2);
                $discountPercentage = $data->discount > 0 ? $data->discount : 0;

                if ($discountPercentage > 0) {
                    $discountAmount = ($data->price * $discountPercentage) / 100;
                    $finalPrice = $data->price - $discountAmount;
                    return '<div>
                        <span class="text-decoration-line-through text-muted">$' . $price . '</span><br>
                        <span class="text-success fw-bold">$' . number_format($finalPrice, 2) . '</span><br>
                        <small class="text-danger">-' . number_format($discountPercentage, 1) . '%</small>
                    </div>';
                }

                return '<span class="fw-bold">$' . $price . '</span>';
            })
            ->addColumn('status', function ($data) {
                $badgeClass = $data->active ? 'bg-success' : 'bg-danger';
                $statusText = $data->active ? __('Active') : __('Inactive');
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->addColumn('features', function ($data) {
                $features = [];
                if ($data->featured) $features[] = '<span class="badge bg-primary me-1">' . __('Featured') . '</span>';
                if ($data->new) $features[] = '<span class="badge bg-info me-1">' . __('New') . '</span>';
                if ($data->best_seller) $features[] = '<span class="badge bg-warning me-1">' . __('Best Seller') . '</span>';
                if ($data->top_rated) $features[] = '<span class="badge bg-success me-1">' . __('Top Rated') . '</span>';

                return implode('', $features) ?: '<span class="text-muted">' . __('None') . '</span>';
            })
            ->rawColumns(['action', 'image', 'category', 'store', 'price', 'status', 'features'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['category', 'store', 'productImages'])
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
            Column::make('image')->title(__('Image'))->addClass('text-center'),
            Column::make('name_en')->title(__('Name (EN)'))->addClass('text-center'),
            Column::make('name_ar')->title(__('Name (AR)'))->addClass('text-center'),
            Column::make('category')->title(__('Category'))->addClass('text-center'),
            Column::make('store')->title(__('Store'))->addClass('text-center'),
            Column::make('price')->title(__('Price'))->addClass('text-center'),
            Column::make('stock')->title(__('Stock'))->addClass('text-center'),
            Column::make('status')->title(__('Status'))->addClass('text-center'),
            Column::make('features')->title(__('Features'))->addClass('text-center'),
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
        return 'Product_' . date('YmdHis');
    }
}
