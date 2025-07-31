<?php

namespace App\DataTables;

use App\Models\Battle;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PanelsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Panel> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('match', function ($row) {
                $logoA = $row->teamA->logo ? asset('storage/' . $row->teamA->logo) : asset('default-logo.png');
                $logoB = $row->teamB->logo ? asset('storage/' . $row->teamB->logo) : asset('default-logo.png');

                $teamA = '<img src="' . $logoA . '" height="50" class="mr-3" style="vertical-align: middle;">' . $row->teamA->name;
                $teamB = $row->teamB->name . '<img src="' . $logoB . '" height="50" class="ml-3" style="vertical-align: middle;">';

                return $teamA . ' <strong>vs</strong> ' . $teamB;
            })
            ->addColumn('action', function ($row) {
                return view('panels.actions', compact('row'))->render();
            })
            ->addColumn('match_date', function ($row) {
                return \Carbon\Carbon::parse($row->match_datetime)->format('d M Y, H:i');
            })
            ->rawColumns(['match', 'action','match_date'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Panel>
     */
    public function query(Battle $model): QueryBuilder
    {
        return $model->newQuery()->with(['teamA', 'teamB'])
                     ->where('tenant_id', auth()->user()->tenant_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('panels-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
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
            Column::make('match'),
            Column::make('match_date'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(80)
            ->title('Control Panel'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Panels_' . date('YmdHis');
    }
}
