<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Category> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                $user = auth()->user();

                $editButton = '';
                $deleteButton = '';

                // Check if the user has permissions for edit
                if ($user->hasRole('admin') || $user->hasPermission('category-update')) {
                    $editButton = '<button class="btn btn-sm btn-warning edit-data" data-id="' . $data->id . '">
                                    <i class="fas fa-edit" style="font-size: 16px;"></i>
                                </button>';
                }

                // Check if the user has permissions for delete
                if ($user->hasRole('admin') || $user->hasPermission('category-delete')) {
                    $deleteButton = '<button class="btn btn-sm btn-danger delete-data" data-id="' . $data->id . '">
                                    <i class="fas fa-trash" style="font-size: 16px;"></i>
                                </button>';
                }

                return $editButton . ' ' . $deleteButton;
            })
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    return '<img src="' . $data->image . '" alt="' . $data->name_en . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('parent', function ($data) {
                return $data->parent ? $data->parent->name_en : '<span class="text-muted">Main Category</span>';
            })
            ->addColumn('level', function ($data) {
                $level = 0;
                $parent = $data->parent;
                while ($parent) {
                    $level++;
                    $parent = $parent->parent;
                }
                $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
                $arrow = $level > 0 ? '└─ ' : '';
                return $indent . $arrow . $data->name_en;
            })
            ->filterColumn('parent', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('parent_categories.name_en', 'like', "%{$keyword}%")
                      ->orWhere('parent_categories.name_ar', 'like', "%{$keyword}%");
                });
            })
            ->filter(function ($query) {
                if (request()->has('search') && request()->get('search')['value']) {
                    $searchValue = request()->get('search')['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('categories.name_en', 'like', "%{$searchValue}%")
                          ->orWhere('categories.name_ar', 'like', "%{$searchValue}%")
                          ->orWhere('parent_categories.name_en', 'like', "%{$searchValue}%")
                          ->orWhere('parent_categories.name_ar', 'like', "%{$searchValue}%");
                    });
                }
            })
            ->rawColumns(['action', 'image', 'parent', 'level'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Category>
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery()
            ->leftJoin('categories as parent_categories', 'categories.parent_id', '=', 'parent_categories.id')
            ->select([
                'categories.*',
                'parent_categories.name_en as parent_name_en',
                'parent_categories.name_ar as parent_name_ar'
            ])
            ->orderBy('categories.parent_id', 'asc')
            ->orderBy('categories.name_en', 'asc');
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
            Column::make('name_en')->title(__('Category Name'))->addClass('text-center'),
            Column::make('name_ar')->title(__('Name (AR)'))->addClass('text-center'),
            Column::make('parent')->title(__('Parent Category'))->addClass('text-center'),
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
        return 'Category_' . date('YmdHis');
    }
}
