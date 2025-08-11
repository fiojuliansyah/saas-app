<?php

namespace App\Imports;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PlayersImport implements ToModel, WithStartRow
{
    private $tenantId;
    private $teamCache = [];

    public function __construct()
    {
        $this->tenantId = Auth::user()->tenant_id;
    }

    public function model(array $row)
    {
        $teamName = $row[0];

        if (empty($teamName)) {
            return null;
        }

        $teamId = $this->getTeamId($teamName);

        if (!$teamId) {
            return null;
        }

        $existingPlayer = Player::where('tenant_id', $this->tenantId)
                                ->where('team_id', $teamId)
                                ->where('name', $row[1])
                                ->first();

        if ($existingPlayer) {
            return null; 
        }

        return new Player([
            'tenant_id' => $this->tenantId,
            'team_id'   => $teamId,
            'name'      => $row[1],
            'nickname'  => $row[2] ?? $row[1],
            'country'   => $row[3] ?? null, 
            'squad'     => $row[4] ?? null, 
            'role'      => $row[5] ?? null, 
            'active'    => filter_var($row[6] ?? false, FILTER_VALIDATE_BOOLEAN),
            'slug'      => Str::slug($row[1]),
        ]);
    }

    private function getTeamId(string $name): ?int
    {
        $teamNameUppercase = strtoupper($name);

        if (isset($this->teamCache[$teamNameUppercase])) {
            return $this->teamCache[$teamNameUppercase];
        }

        $team = Team::where('tenant_id', $this->tenantId)
                    ->whereRaw('UPPER(name) = ?', [strtoupper($teamNameUppercase)])
                    ->first();

        $this->teamCache[$teamNameUppercase] = $team ? $team->id : null;

        return $this->teamCache[$teamNameUppercase];
    }

    public function startRow(): int
    {
        return 2;
    }
}
