<template>
  <div class="p-header">
    <!-- ロゴ -->
    <div class="p-header__left">
      <a class="p-header__logo" href="/recordlist">
        <img
          src="../../../storage/app/public/images/logos/udemyload_logo.svg"
          alt="UdemyLoad"
          class="p-header__logo--img"
        />
      </a>
    </div>

    <!-- 右側 -->
    <div class="p-header__right">
      <!-- SPサイト用メニュー -->
      <div
        class="p-header__trigger"
        :class="{ active: isActive }"
        @click="toggleSpMenu"
      >
        <span class="p-header__trigger--bar"></span>
        <span class="p-header__trigger--bar"></span>
        <span class="p-header__trigger--bar"></span>
      </div>

      <!-- メニュー -->
      <nav
        class="p-header__nav p-header__nav__sp"
        :class="{ active: isActive }"
      >
        <ul class="p-header__menu">
          <!-- 投稿ボタン(仮)(ログイン中の場合) -->
          <li v-if="isLogin" class="p-header__item">
            <a class="p-header__item--link c-btn__header" href="/records/new">
              投稿する
            </a>
          </li>

          <!-- ユーザーネーム(ログイン中の場合) -->
          <li v-if="isLogin" class="p-header__item">
            <a
              class="p-header__item--link c-btn__header"
              :href="`/mypage/${this.username}`"
            >
              マイページ
            </a>
          </li>

          <!-- ログアウトボタン -->
          <li v-if="isLogin" class="p-header__item">
            <button class="p-header__item--link c-btn__header" @click="logout">
              ログアウト
            </button>
          </li>

          <!-- ログインボタン(未ログイン時) -->
          <li v-else class="p-header__item">
            <a class="p-header__item--link c-btn__header" href="/login">
              ログイン / 新規登録
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isActive: false,
    };
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus;
    },
    isLogin() {
      return this.$store.getters['auth/check'];
    },
    username() {
      return this.$store.getters['auth/username'];
    },
  },
  methods: {
    // ナビバー切り替え
    toggleSpMenu() {
      this.isActive = !this.isActive;
    },
    // ログアウト
    async logout() {
      await this.$store.dispatch('auth/logout');
      // 処理成功時のみ遷移
      if (this.apiStatus) {
        // トップに遷移させる
        this.$router.go({
          path: `/`,
          force: true,
        });
      }
    },
  },
};
</script>
