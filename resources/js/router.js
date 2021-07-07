import Vue from 'vue';
import VueRouter from 'vue-router';

// コンポーネント
import RecordList from './pages/Records/RecordList.vue';
import Search from './pages/Search/Search.vue';
import Info from './pages/Info/Info.vue';
import Mypage from './pages/Mypage/Mypage.vue';
import RecordCreate from './pages/Records/RecordCreate.vue';
import RecordDetail from './pages/Records/RecordDetail.vue';
import Login from './pages/Auth/Login.vue';

// 設定画面
import Account from './pages/Settings/Account.vue';
import Profile from './pages/Settings/Profile.vue';
import PasswordMenu from './pages/Settings/PasswordMenu.vue';

// エラーコンポーネント
import SystemError from './pages/Errors/500_System.vue';
import NotFound from './pages/Errors/404_NotFound.vue';

// storeのインポート
import store from './store';
// VueRouterプラグインの使用
Vue.use(VueRouter);

// vue-routerからvuexを参照するには直接インポートする
// 認証切れの場合その場でリロードし、/loginへ遷移する
async function checkLoginToken(to, from, next) {
  await auth.actions.check_authenticate();
  if (!auth.state.authenticate) {
    next(true);
  } else {
    document.location.reload();
  }
}
// ナビゲーションガード用/ログイン済みの場合は見ることができない
async function checkAuth(to, from, next) {
  if (store.getters['auth/check']) {
    next('/');
  } else {
    next();
  }
}
// ログインしていない場合、ログイン画面に遷移させる
async function requireAuth(to, from, next) {
  if (store.getters['auth/check']) {
    next();
  } else {
    next('/login');
  }
}

// パスとコンポーネントをマッピング
const routes = [
  {
    path: '/',
    component: Info,
  },
  {
    path: '/recordlist',
    component: RecordList,
  },
  {
    path: '/search',
    component: Search,
  },
  {
    path: '/login',
    component: Login,
    beforeEnter: checkAuth,
  },
  {
    // マイページ
    path: '/mypage/:username',
    component: Mypage,
    props: true,
  },
  {
    // 新規投稿ページ
    path: '/records/new',
    component: RecordCreate,
    beforeEnter: requireAuth,
  },
  {
    // コースレコード詳細ページ
    path: '/records/:id',
    component: RecordDetail,
    props: true,
  },
  {
    // コースレコード編集ページ
    path: '/records/:id/edit',
    component: RecordCreate,
    props: true,
    beforeRouteEnter: requireAuth,
  },
  {
    // アカウント設定
    path: '/settings/account',
    component: Account,
    beforeEnter: requireAuth,
    props: true,
  },
  {
    // プロフィール設定
    path: '/settings/profile',
    component: Profile,
    beforeEnter: requireAuth,
    props: true,
  },
  {
    // パスワード設定
    path: '/settings/password',
    component: PasswordMenu,
    beforeEnter: requireAuth,
    props: true,
  },
  {
    path: '/500',
    component: SystemError,
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
