<?php

namespace App\DataTables;

use App\Models\Team;
use App\Models\Battle;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class BattlesDataTable extends DataTable
{
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
            ->addColumn('score', function ($row) {
                $scoreA = $row->score_team_a ?? 0;
                $scoreB = $row->score_team_b ?? 0;

                // badge markup
                $badgeWin  = '<span class="mr-1" style="color: green;"> Win </span>';
                $badgeLose = '<span class="ml-1" style="color: red;"> Lose </span>';
                $badgeDraw = '';

                if ($scoreA > $scoreB) {
                    $scoreA = $badgeWin . $scoreA;
                    $scoreB = $scoreB . $badgeLose;
                } elseif ($scoreB > $scoreA) {
                    $scoreA = $badgeLose . $scoreA;
                    $scoreB = $scoreB . $badgeWin;
                } else {
                    $scoreA = $badgeDraw . $scoreA;
                    $scoreB = $scoreB . $badgeDraw;
                }

                return "{$scoreA} - {$scoreB}";
            })

            ->addColumn('match_date', function ($row) {
                return \Carbon\Carbon::parse($row->match_date)->format('d M Y');
            })
            ->addColumn('match_time', function ($row) {
                return \Carbon\Carbon::parse($row->match_time)->format('H:i');
            })
            ->addColumn('action', function ($row) {
                $teams = Team::all();
                return view('battles.actions', compact('row','teams'))->render();
            })
            ->rawColumns(['action','match','score','match_date','match_time'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Battle>
     */
    public function query(Battle $model): QueryBuilder
    {
        return $model->newQuery()
                     ->with(['teamA', 'teamB'])
                     ->where('tenant_id', auth()->user()->tenant_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('battles-table')
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
            Column::make('score'),
            Column::make('match_date'),
            Column::make('match_time'),
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
        return 'Battles_' . date('YmdHis');
    }
}
