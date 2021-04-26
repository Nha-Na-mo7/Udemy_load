<!-- トップコンポーネント -->
<template>
  <div>
    <!-- ヘッダー -->
    <header id="header" class="l-header">
      <Header />
    </header>

    <!-- メインコンテンツ -->
    <div class="l-main">
      <!-- 実際にコンポーネントが切り替わるエリア -->
      <RouterView />
    </div>
  </div>
</template>

<script>
import Header from './components/Header.vue';
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util.js';
export default {
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    },
  },
  components: {
    Header,
  },
  watch: {
    errorCode: {
      async handler(val) {
        // 500エラーの時
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500');
        } else if (val === UNAUTHORIZED) {
          //  419エラー:認証切れの時・トークンリフレッシュ
          await axios.get('/refresh-token');
          // ストアのuserをクリアする
          this.$store.commit('auth/setUser', null);
          // ログイン画面に遷移
          this.$router.push('/login');
          //TODO 404エラーの時。ただし変更の余地あり
        } else if (val === NOT_FOUND) {
          this.$router.push('/not_found');
        }
      },
      // 初期化時にも判定させる
      immediate: true,
    },
    $route() {
      this.$store.commit('error/setCode', null);
    },
  },
};
</script>

<style scoped></style>
