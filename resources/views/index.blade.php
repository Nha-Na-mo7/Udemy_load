{{-- =========================== --}}
{{--  ユーザーが最初に訪れるページです --}}
{{-- =========================== --}}
@extends('layouts.app')

@section('title', 'Udemyに特化した学習記録サービス')

{{-- descriptionはSPユーザーも想定して、90文字行かない程度が理想 --}}
@section('description', 'Udemyの学習の記録をロードマップにまとめよう！どの順番でUdemyの各講座を受講すればいいかをまとめて、いろいろな人にあなたの学びやオススメの講座を薦めよう！')
@section('keywords', 'Udemy, ユーデミー, ロードマップ, プログラミング, 初心者, 未経験, エンジニア, プログラミングスクール, プログラミング学習')


@section('content')
    <section class="p-landing__section p-landing__information">
        <div class="p-landing__container">
            <div class="p-landing__section--info">
                <h1 class="p-landing__section--title">
                    UdemyLoadは<br />
                    「Udemy」 に特化した学習記録サービスです。
                </h1>
                <pre class="p-landing__section--text">
                    「Udemyの講座を組み合わせればプログラミングスクールはいらない」
                    「良質なUdemyの講座ならたくさんある」
                    ...とよく聞くけれど、肝心のどんな講座の組み合わせが良いかは教えてくれない。

                    そんな悩みはUdemyLoadで一瞬で解決します！
                </pre>
            </div>
        </div>
    </section>
@endsection
