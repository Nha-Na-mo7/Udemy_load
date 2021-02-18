import Vue from 'vue';
import VueRouter from 'vue-router';

// コンポーネントのインストール
import RecordList from './pages/Records/RecordList.vue';
import Login from './pages/Auth/Login.vue';

// VueRouterプラグインの使用
Vue.use(VueRouter);

// vue-routerからvuexを参照するには直接インポートする
// 認証切れの場合に"/login"へ遷移させる
async function requireLogin(to, from, next) {
  await auth.actions.check_authenticate();
  if (!auth.state.authenticate) {
    next(true);
  } else {
    document.location.reload();
  }
}

// パスとコンポーネントをマッピング
const routes = [
  {
    path: '/',
    component: RecordList
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '*',
    component: NotFound404,
  },
];

const router = new VueRouter({
  mode: 'history',
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
  routes,
});

// VueRouterインスタンスのエクスポート(app.jsでインポートする)
export default router;
