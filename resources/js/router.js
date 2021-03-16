import Vue from 'vue';
import VueRouter from 'vue-router';

// コンポーネントのインストール
import RecordList from './pages/Records/RecordList.vue';
import Mypage from './pages/Mypage/Mypage.vue';
import RecordEdit from './pages/Records/Edit.vue';
import RecordDetail from './pages/Records/RecordDetail.vue';
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
    path: '/mypage/:user_id',
    component: Mypage,
    props: (route) => {
      const page = route.query.page
      return {
        // 整数でない値を1扱いにする
        page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1,
        // mypage/:user_id のuser_idの部分
        user_id: Number(route.params.user_id)
      }
    }
  },
  {
    path: '/records/new',
    component: RecordEdit,
    beforeEnter: requireAuth
  },
  {
    // コースレコード詳細ページ
    path: '/records/:id',
    component: RecordDetail,
    props: true
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
