require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// ルーティングの定義
import router from './router.js';
// ルートコンポーネント
import App from './App.vue';

// Vueインスタンスを作成するメソッド
const createApp = () => {
    // id="app"がHTMLに存在する時のみVueインスタンスを作成
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
