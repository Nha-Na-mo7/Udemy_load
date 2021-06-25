<!-- =========-->
<!--  ホーム -->
<!-- ==========-->
@extends('layouts.app')
@section('title', 'Udemyに特化したロードマップ記録サービス')
{{-- description90文字程度 --}}
@section('description', 'Udemyの学習の記録をロードマップにまとめよう！どの順番でUdemyの各講座を受講すればいいかをまとめて、いろいろな人にあなたの学びやオススメの講座を薦めよう！')
@section('keywords', 'Udemy, ユーデミー, ロードマップ, プログラミング, 初心者, 未経験, エンジニア, プログラミングスクール, プログラミング学習')

@section('content')
@if (session('flash_msg'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
    <div id="app"></div>
@endsection