<?php

namespace App\DataTables;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TeamsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Team> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('teams.actions', compact('row'))->render();
            })
            ->addColumn('logo', function ($row) {
                return $row->logo ? '<img src="' . asset('storage/' . $row->logo) . '" height="40"/>' : '-';
            })
            ->addColumn('country', function ($row) {
                return $row->country ? '<img src="' . asset('assets/images/flags/' . $row->country . '.png') . '" height="20"/>' : '-';
            })
            ->rawColumns(['logo', 'action', 'country'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Team>
     */
    public function query(Team $model): QueryBuilder
    {
        return $model->newQuery()->where('tenant_id', auth()->user()->tenant_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('teams-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addTableClass('table custom-table align-middle text-white') 
            ->orderBy(1)
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
            Column::make('logo')->title('Logo')->orderable(false)->searchable(false),
            Column::make('name'),
            Column::make('short_name')->title('Short Name'),
            Column::make('country'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(80)
            ->title('Actions'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Teams_' . date('YmdHis');
    }
}
