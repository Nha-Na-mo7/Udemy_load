import Vue from 'vue';
import VueRouter from 'vue-router';

// コンポーネントのインストール
import RecordList from './pages/Records/RecordList.vue';
import RecordEdit from './pages/Records/Edit.vue';
import Login from './pages/Auth/Login.vue';

// エラーコンポーネント
import SystemError from './pages/Errors/500_System.vue';
import NotFound from './pages/Errors/404_NotFound.vue';

// storeのインポート
import store from './store'
// VueRouterプラグインの使用
Vue.use(VueRouter);

// vue-routerからvuexを参照するには直接インポートする
// 認証切れの場合に"/login"へ遷移させる
async function checkLoginToken(to, from, next) {
  await auth.actions.check_authenticate();
  if (!auth.state.authenticate) {
    next(true);
  } else {
    document.location.reload();
  }
}
// ナビゲーションガード用/ログイン済みの場合は見ることができない
async function checkAuth(to, from ,next) {
  if (store.getters['auth/check']) {
    next('/')
  } else {
    next()
  }
}
// ログインしていない場合、ログイン画面に遷移させる
async function requireAuth(to, from ,next) {
  if (store.getters['auth/check']) {
    next()
  } else {
    next('/login')
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
    component: Login,
    beforeEnter: checkAuth
  },
  {
    path: '/record/new',
    component: RecordEdit,
    beforeEnter: requireAuth
  },
  {
    path: '/500',
    component:  SystemError,
  },
  {
    path: '*',
    component: NotFound,
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
