require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// Vueインスタンスを作成するメソッド
const createApp = () => {
    if (document.getElementById('app') != null) {
        new Vue({
            el: '#app',
            router, // ルーティング定義の読み込み
            components: { App }, // ルートコンポーネントの使用宣言
            template: '<App />' //ルートコンポーネントの描画
        });
    }
}

// 上記メソッドの呼び出し
createApp();
