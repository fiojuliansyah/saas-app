@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">HUDS</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">HUDS</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <div class="me-4">
                                <img src="/assets/images/thumb.png" alt="HUD Thumbnail" style="width: 60px; height: auto; border-radius: 6px;">
                            </div>

                            <div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <h5 class="mb-0 ml-4 mr-3">Classic HUD - Delta Force</h5>
                                    <span class="text-muted">Version: v1.0</span>
                                </div>

                                <div class="d-flex flex-wrap gap-3">
                                    @php
                                        $features = [
                                            ['icon' => 'fa-desktop', 'label' => 'Cover'],
                                            ['icon' => 'fa-users', 'label' => 'Players'],
                                            ['icon' => 'fa-gamepad', 'label' => 'Game Format'],
                                            ['icon' => 'fa fa-exchange', 'label' => 'Side Swap'],
                                            ['icon' => 'fa-area-chart', 'label' => 'Statistics'],
                                            ['icon' => 'fa-sitemap', 'label' => 'Bracket'],
                                            ['icon' => 'fa-gavel', 'label' => 'Map Pick'],
                                            ['icon' => 'fa-user-circle-o', 'label' => 'Player Cam'],
                                            ['icon' => 'fa-headphones', 'label' => 'Caster Cam'],
                                            ['icon' => 'fa-trophy', 'label' => 'Victory'],
                                            ['icon' => 'fa fa-backward', 'label' => 'Replay'],
                                        ];
                                    @endphp
                                    @foreach ($features as $feature)
                                        <div class="text-center text-white" style="width: 70px;">
                                            <i class="fa {{ $feature['icon'] }}" style="font-size: 18px;"></i>
                                            <div style="font-size: 10px;">{{ $feature['label'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <a href="#" title="Preview HUD" style="color: white; font-size: 18px;">
                                <i class="fa fa-link mr-3"></i>
                            </a>
                            <a href="#" title="Settings" style="color: white; font-size: 18px;">
                                <i class="fa fa-cog mr-3"></i>
                            </a>

                            <label class="switch" style="margin-top: 10px;">
                                <input type="checkbox" name="active">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
