<?php

namespace App\Imports;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeamsImport implements ToModel, WithStartRow 
{
    private $tenantId;

    public function __construct()
    {
        $this->tenantId = Auth::user()->tenant_id;
    }

    public function model(array $row)
    {
        return new Team([
            'tenant_id'  => $this->tenantId,
            'name'       => strtoupper($row[0]),
            'short_name' => $row[1],
            'country'    => $row[2] ?? null,
            'slug'       => Str::slug($row[0]),
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}