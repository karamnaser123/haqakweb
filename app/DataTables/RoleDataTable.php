<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Role> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                $user = auth()->user();

                $editButton = '';
                $permissionsButton = '';

                // Permissions button
                if ($user->hasRole('admin') || $user->hasPermission('role-permission')) {
                    $permissionsButton = '<button class="btn btn-sm btn-info permissions-btn" data-id="' . $data->id . '">
                                        <i class="fas fa-key" style="font-size: 16px;"></i>
                                    </button>';
                }

                // Check if the user has permissions for edit
                if ($user->hasRole('admin') || $user->hasPermission('role-update')) {
                    $editButton = '<button class="btn btn-sm btn-warning edit-data" data-id="' . $data->id . '">
                                    <i class="fas fa-edit" style="font-size: 16px;"></i>
                                </button>';
                }

                return $permissionsButton . ' ' . $editButton;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Role>
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()->where('name', '!=', 'admin');
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
            Column::make('display_name')->title(__('Display Name'))->addClass('text-center'),
            Column::make('description')->title(__('Description'))->addClass('text-center'),
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
        return 'Role_' . date('YmdHis');
    }
}
