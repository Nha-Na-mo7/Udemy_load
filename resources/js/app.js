// Ajax通信用のAxiosライブラリの設定が記述されている、デフォルトのjsファイル
import './bootstrap.js';

// jQuery
import jQuery from './jquery.js';

// Vue.js
import Vue from 'vue';
// ルーティングの定義
import router from './router.js';
// ストアのインポート
import store from './store';
// ルートコンポーネント
import App from './App.vue';

// Vueインスタンスを作成するメソッド
const createApp = async () => {
    // id="app"がHTMLに存在する時のみVueインスタンスを作成
    if (document.getElementById('app') != null) {
        
        // Vueインスタンス生成前に現在のユーザー情報をストアに格納
        await store.dispatch('auth/currentUser')
 
        // Vueインスタンスの生成
        new Vue({
            el: '#app',
            router, // ルーティング定義
            store, // ストア
            jQuery,
            components: { App }, // ルートコンポーネントの使用宣言
            template: '<App />' //ルートコンポーネントの描画
        });
    }
}

// 上記メソッドの呼び出し
createApp();
