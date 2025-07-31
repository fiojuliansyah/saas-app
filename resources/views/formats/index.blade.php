@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Game Formats</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Game Formats</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="card-title m-0">Pengaturan Format Pertandingan</h4>
                        </div>

                        {{-- Menampilkan pesan sukses atau error --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('formats.updateOrCreate') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Format Tim</label>
                                            <select name="team_format" class="form-control">
                                                <option value="warefare" {{ old('team_format', $format->team_format) == 'warefare' ? 'selected' : '' }}>Warefare</option>
                                                <option value="operation" {{ old('team_format', $format->team_format) == 'operation' ? 'selected' : '' }}>Operation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Mode Game</label>
                                            <input type="text" name="game_mode" class="form-control" placeholder="cth: Best of 3" value="{{ old('game_mode', $format->game_mode) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Kondisi Menang (Win Condition)</label>
                                            <input type="text" name="win_condition" class="form-control" placeholder="cth: First to 2 wins" value="{{ old('win_condition', $format->win_condition) }}">
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mt-4">Detail Game (Opsional)</h5>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Game 1</label>
                                            <input type="text" name="game_1" class="form-control" placeholder="cth: Hardpoint - Standoff" value="{{ old('game_1', $format->game_1) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Game 2</label>
                                            <input type="text" name="game_2" class="form-control" placeholder="cth: S&D - Firing Range" value="{{ old('game_2', $format->game_2) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Game 3</label>
                                            <input type="text" name="game_3" class="form-control" placeholder="cth: Control - Raid" value="{{ old('game_3', $format->game_3) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-check"></i> Simpan Format</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection