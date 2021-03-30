<template>
  <div class="p-header">

    <!-- ロゴ -->
    <div class="p-header__left">
      <RouterLink class="p-header__logo" to="/">
        <img src="" alt="UdemyLoad" class="p-header__logo--img">
      </RouterLink>
    </div>

    <!-- SPサイト用メニュー -->
    <div class="p-header__trigger js-toggle-sp-menu">
      <span class="p-header__trigger--bar"></span>
      <span class="p-header__trigger--bar"></span>
      <span class="p-header__trigger--bar"></span>
    </div>

    <!-- メニュー -->
    <div class="p-header__right">
      <nav class="p-header__nav p-header__nav__sp js-toggle-sp-nav">
        <ul class="p-header__menu">

          <!-- 投稿ボタン(仮)(ログイン中の場合) -->
          <li v-if="isLogin" class="p-header__item">
            <RouterLink class="c-btn__header" to="/records/new">
              投稿する
            </RouterLink>
          </li>

          <!-- ユーザーネーム(ログイン中の場合) -->
          <li v-if="isLogin" class="p-header__item">
            <RouterLink class="c-btn__header" :to="`/mypage/${ this.username }`">
              {{ username | addAtSign }}
            </RouterLink>
          </li>

          <!-- ログアウトボタン -->
          <li
              v-if="isLogin"
              class="p-header__item"
          >
            <button
                class="c-btn__header"
                @click="logout">
              ログアウト
            </button>
          </li>

          <!-- ログインボタン(未ログイン時) -->
          <li v-else class="p-header__item">
            <RouterLink class="c-btn__header" to="/login">
              ログイン / 新規登録
            </RouterLink>
          </li>

        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus
    },
    isLogin() {
      return this.$store.getters['auth/check']
    },
    username() {
      return this.$store.getters['auth/username']
    },
  },
  methods: {
    // ログアウト
    async logout() {
      await this.$store.dispatch('auth/logout')
      // 処理成功時のみ遷移
      if (this.apiStatus) {
        this.$router.push('/login')
      }
    }
  },
  filters: {
    addAtSign: function (username) {
      return '@' + username
    }
  }
}
</script>