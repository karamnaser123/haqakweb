<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                $user = \Auth::user();

                $editButton = '';
                $statusButton = '';
                $permissionsButton = '';

                // Status button
                if ($user->hasRole('admin') || $user->hasPermission('view-users')) {
                    $statusButton = '<button class="btn btn-sm '. ($data->active ? 'btn-info' : 'btn-danger') .' toggle-status " data-id="' . $data->id . '">
                                    <i class="fas ' . ($data->active ? 'fa-toggle-on' : 'fa-toggle-off') . '" style="font-size: 16px;"></i>
                                </button>';
                }

                // Permissions button
                if ($user->hasRole('admin') || $user->hasPermission('user-permission')) {
                    $permissionsButton = '<button class="btn btn-sm btn-success permissions-btn" data-id="' . $data->id . '">
                                        <i class="fas fa-key" style="font-size: 16px;"></i>
                                    </button>';
                }

                // Check if the user has permissions for edit
                if ($user->hasRole('admin') || $user->hasPermission('user-update')) {
                    $editButton = '<button class="btn btn-sm btn-warning edit-data" data-id="' . $data->id . '">
                                    <i class="fas fa-edit" style="font-size: 16px;"></i>
                                </button>';
                }

                return $statusButton . ' ' . $permissionsButton . ' ' . $editButton;
            })
            ->addColumn('active', function ($data) {
                return $data->active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'active'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->where('id', '!=',  '1')
        ->newQuery();
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
            Column::make('name')->title(__('Name'))->addClass('text-center'),
            Column::make('email')->title(__('Email'))->addClass('text-center'),
            Column::make('phone')->title(__('Phone'))->addClass('text-center'),
            Column::make('gender')->title(__('Gender'))->addClass('text-center'),
            Column::make('age')->title(__('Age'))->addClass('text-center'),
            Column::make('balance')->title(__('Balance'))->addClass('text-center'),
            Column::make('code')->title(__('Code'))->addClass('text-center'),
            Column::make('active')->title(__('Active'))->addClass('text-center'),
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
        return 'User_' . date('YmdHis');
    }
}
