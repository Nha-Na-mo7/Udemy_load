<template>
  <div class="l-container__auth">

    <div class="p-auth">
      <!-- 切り替えたぶ -->
      <ul class="p-auth__tab c-tab">
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
      <section class="c-panel" v-show="tab === 1">
        <form
            class="p-form__auth"
            @submit.prevent="login"
        >
          <label for="login-email">メールアドレス</label>
          <ul v-if="loginErrors">
            <li
                class="c-error"
                v-for="msg in loginErrors.email"
                :key="msg"
            >{{ msg }}</li>
          </ul>
          <input
              type="text"
              class="c-input c-form__input"
              id="login-email"
              v-model="loginForm.email"
          >

          <label for="login-password">パスワード</label>
          <ul v-if="loginErrors">
            <li
                class="c-error"
                v-for="msg in loginErrors.password"
                :key="msg"
            >{{ msg }}</li>
          </ul>
          <input
              type="password"
              class="c-input c-form__input"
              id="login-password"
              v-model="loginForm.password"
          >

          <!-- remember me -->
          <div class="c-form__info c-form__checkbox">
            <label for="remember">
              <input
                  type="checkbox"
                  name="remember"
                  id="remember"
                  class="c-form__checkbox--check"
                  v-model="loginForm.remember"
              >
              ログイン状態を維持する
            </label>
          </div>

          <div class="form__button">
            <button type="submit" class="c-btn c-btn__auth">ログイン</button>
          </div>
        </form>
      </section>

      <!-- 新規登録フォーム -->
      <section class="c-panel" v-show="tab === 2">
        <form class="p-form__auth" @submit.prevent="register">

          <label for="username">ユーザーネーム</label>
          <ul v-if="registerErrors">
            <li
                class="c-error"
                v-for="msg in registerErrors.name"
                :key="msg"
            >{{ msg }}</li>
          </ul>
          <input type="text"
                 class="c-input c-form__input"
                 id="username"
                 v-model="registerForm.name"
          >

          <label for="email">メールアドレス</label>
          <ul v-if="registerErrors">
            <li
                class="c-error"
                v-for="msg in registerErrors.email"
                :key="msg"
            >{{ msg }}</li>
          </ul>
          <input
              type="text"
              class="c-input c-form__input"
              id="email"
              v-model="registerForm.email"
          >

          <label for="password">パスワード</label>
          <ul v-if="registerErrors">
            <li
                class="c-error"
                v-for="msg in registerErrors.password"
                :key="msg"
            >{{ msg }}</li>
          </ul>
          <input
              type="password"
              class="c-input c-form__input"
              id="password"
              v-model="registerForm.password"
          >
          <label for="password-confirmation">パスワードの再入力</label>
          <input
              type="password"
              class="c-input c-form__input"
              id="password-confirmation"
              v-model="registerForm.password_confirmation"
          >
          <div class="p-form__button">
            <button type="submit" class="c-btn c-btn__auth">新規登録</button>
          </div>
        </form>
      </section>
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
        password: '',
        remember: false,
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
    registerErrors() {
      return this.$store.state.auth.registerErrorMessages
    },
    loginErrors() {
      return this.$store.state.auth.loginErrorMessages
    },
  },
  methods: {
    // ======================================
    // 新規会員登録(authストアのregisterアクション)
    // ======================================
    async register() {
      await this.$store.dispatch('auth/register', this.registerForm)
      // ログイン成功時、遷移させる
      if (this.apiStatus) {
        // トップに遷移させる
        this.$router.go({
          path: `/`,
          force: true
        })
      }
    },
    // =======
    // ログイン
    // =======
    async login() {
      await this.$store.dispatch('auth/login', this.loginForm)
      if (this.apiStatus) {
        // トップに遷移させる
        this.$router.go({
          path: `/`,
          force: true
        })
      }
    },
    // ====================
    // エラーメッセージのクリア
    // ====================
    clearError() {
      this.$store.commit('auth/setRegisterErrorMessages', null)
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  },
  created() {
    this.clearError()
  }
}
</script>