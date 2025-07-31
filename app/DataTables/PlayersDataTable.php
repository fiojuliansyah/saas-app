<?php

namespace App\DataTables;

use App\Models\Team;
use App\Models\Player;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PlayersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Player> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
             ->addColumn('action', function ($row) {
                $teams = Team::all();
                return view('players.actions', compact('row','teams'))->render();
            })
            ->addColumn('avatar', function ($row) {
                return $row->avatar ? '<img src="' . asset('storage/' . $row->avatar) . '" height="40"/>' : '-';
            })
            ->addColumn('country', function ($row) {
                return $row->country ? '<img src="' . asset('assets/images/flags/' . $row->country . '.png') . '" height="20"/>' : '-';
            })
            ->addColumn('team_name', function (Player $player) {
                return $player->team ? '<img src="' . asset('storage/' . $player->team->logo) . '" height="40" style="margin-right: 10px;" />' . '' . $player->team->name : '-';
            })
            ->rawColumns(['avatar', 'action', 'country', 'team_name'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Player>
     */
    public function query(Player $model): QueryBuilder
    {
        return $model->with(['team'])->newQuery()->where('tenant_id', auth()->user()->tenant_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('players-table')
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
            Column::make('avatar')->title('Avatar')->orderable(false)->searchable(false),
            Column::make('name'),
            Column::make('nickname'),
            Column::make('team_name')->title('Team'), 
            Column::make('squad'),
            Column::make('country')->title('Squad Name'),
            Column::make('role'),
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
        return 'Players_' . date('YmdHis');
    }
}
