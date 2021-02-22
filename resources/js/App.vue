<!-- トップコンポーネント -->
<template>
  <div>
    <!-- ヘッダー -->
    <header id="header" class="l-header">
      <Header />
    </header>

    <!-- メインコンテンツ -->
    <main>
      <div>
        <!-- 実際にコンポーネントが切り替わるエリア -->
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script>
import Header from './components/Header.vue';
import { INTERNAL_SERVER_ERROR } from './util.js';
export default {
  computed: {
    errorCode() {
    return this.$store.state.error.code
    }
  },
  components: {
    Header,
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      // 初期化時にも判定させる
      immediate: true
    },
    $route() {
      this.$store.commit('error/setCode', null)
    }
  }
};
</script>