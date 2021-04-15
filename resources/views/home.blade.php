<!-- =========-->
<!--  ホーム -->
<!-- ==========-->
@extends('layouts.app')
@section('title', 'Udemyに特化した学習記録サービス')
@section('content')
@if (session('flash_msg'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
{{--    <div class="container">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-8">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header">Dashboard</div>--}}

{{--                    <div class="card-body">--}}
{{--                        @if (session('status'))--}}
{{--                            <div class="alert alert-success" role="alert">--}}
{{--                                {{ session('status') }}--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        You are logged in!--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div id="app"></div>
@endsection
