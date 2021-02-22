<template>
  <div class="l-container__auth">
    <ul class="c-tab">
      <li
          class="c-tab__item"
          :class="{'c-tab__item--active': tab === 1 }"
          @click="tab = 1"
      >ログイン</li>
      <li
          class="c-tab__item"
          :class="{'c-tab__item--active': tab === 2 }"
          @click="tab = 2"
      >新規登録</li>
    </ul>

    <!-- ログインフォーム -->
    <div class="c-panel" v-show="tab === 1">
      <form
          class="p-form__auth"
          @submit.prevent="login"
      >
        <!-- エラーメッセージ -->
        <div
            v-if="loginErrors"
            class="c-errors"
        >
          <ul v-if="loginErrors.email">
            <li
                v-for="msg in loginErrors.email"
                :key="msg"
            >{{ msg }}
            </li>
          </ul>
          <ul v-if="loginErrors.password">
            <li
                v-for="msg in loginErrors.password"
                :key="msg"
            >{{ msg }}
            </li>
          </ul>
        </div>

        <label for="login-email">メールアドレス</label>
        <input
            type="text"
            class="c-input"
            id="login-email"
            v-model="loginForm.email"
        >
        <label for="login-password">パスワード</label>
        <input
            type="password"
            class="c-input"
            id="login-password"
            v-model="loginForm.password"
        >
        <div class="form__button">
          <button type="submit" class="c-btn c-btn__auth">ログイン</button>
        </div>
      </form>
    </div>

    <!-- 新規登録フォーム -->
    <div class="c-panel" v-show="tab === 2">
      <form class="p-form__auth" @submit.prevent="register">
        <label for="username">ユーザーネーム</label>
        <input type="text"
               class="c-input"
               id="username"
               v-model="registerForm.name"
        >
        <label for="email">メールアドレス</label>
        <input
            type="text"
            class="c-input"
            id="email"
            v-model="registerForm.email"
        >
        <label for="password">パスワード</label>
        <input
            type="password"
            class="c-input"
            id="password"
            v-model="registerForm.password"
        >
        <label for="password-confirmation">パスワードの再入力</label>
        <input
            type="password"
            class="c-input"
            id="password-confirmation"
            v-model="registerForm.password_confirmation"
        >
        <div class="p-form__button">
          <button type="submit" class="c-btn c-btn__auth">新規登録</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      tab: 1,
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
    }
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus
    },
    loginErrors() {
      return this.$store.state.auth.loginErrorMessages
    }
  },
  methods: {
    // --------------------------------------
    // 新規会員登録(authストアのregisterアクション)
    // --------------------------------------
    async register() {
      await this.$store.dispatch('auth/register', this.registerForm)
      // 遷移
      this.$router.push('/')
    },
    // ---------
    // ログイン
    // ---------
    async login() {
      await this.$store.dispatch('auth/login', this.loginForm)
      // ログイン成功時、遷移させる
      if (this.apiStatus) {
        this.$router.push('/')
      }
    },
    // --------------------
    // エラーメッセージのクリア
    // --------------------
    clearError() {
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  },
  created() {
    this.clearError()
  }
}
</script>