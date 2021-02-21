<template>
  <nav class="p-header__nav p-header__nav__sp js-toggle-sp-nav">
    <RouterLink class="p-header__logo" to="/">
      UdemyLoad
    </RouterLink>
    <div class="p-header__menu">
      <div class="p-header__item">
        <button class="c-btn">
          <i class=""></i>
          投稿する
        </button>
      </div>
      <span class="p-header__item">
        username
      </span>
      <div class="p-header__item">
        <RouterLink class="c-btn button--link" to="/login">
          ログイン / 新規登録
        </RouterLink>
      </div>

      <button
          class="c-btn"
          @click="logout"
      >ログアウト</button>
    </div>
  </nav>
</template>

<script>
export default {
  methods: {
    // ログアウト
    async logout() {
      await this.$store.dispatch('auth/logout')

      this.$router.push('/login')
    }
  }
}
</script>