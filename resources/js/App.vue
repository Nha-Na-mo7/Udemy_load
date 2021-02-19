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
export default {
  components: {
    Header,
  },
};
</script>