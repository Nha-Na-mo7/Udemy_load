<template>
  <nav class="p-header__nav p-header__nav__sp js-toggle-sp-nav">
    <RouterLink class="p-header__logo" to="/">
      UdemyLoad
    </RouterLink>
    <div class="p-header__menu">

      <!-- 投稿ボタン(仮)(ログイン中の場合) -->
      <div v-if="isLogin" class="p-header__item">
        <button class="c-btn">
          <i class=""></i>
          投稿する
        </button>
      </div>

      <!-- ユーザーネーム(ログイン中の場合) -->
      <span v-if="isLogin" class="p-header__item">
        {{ username }}
      </span>

      <!-- ログアウトボタン -->
      <button
          v-if="isLogin"
          class="c-btn"
          @click="logout"
      >ログアウト</button>

      <!-- ログインボタン(未ログイン時) -->
      <div v-else class="p-header__item">
        <RouterLink class="c-btn button--link" to="/login">
          ログイン / 新規登録
        </RouterLink>
      </div>

    </div>
  </nav>
</template>

<script>
export default {
  computed: {
    isLogin() {
      return this.$store.getters['auth/check']
    },
    username() {
      return this.$store.getters['auth/username']
    }
  },
  methods: {
    // ログアウト
    async logout() {
      await this.$store.dispatch('auth/logout')

      this.$router.push('/login')
    }
  }
}
</script>