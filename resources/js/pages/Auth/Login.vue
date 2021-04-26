<template>
  <div class="l-container__auth">
    <div class="p-auth">
      <!-- 切り替えたぶ -->
      <ul class="p-auth__tab c-tab">
        <li
          class="c-tab__item"
          :class="{ 'c-tab__item--active': tab === 1 }"
          @click="tab = 1"
        >
          ログイン
        </li>
        <li
          class="c-tab__item"
          :class="{ 'c-tab__item--active': tab === 2 }"
          @click="tab = 2"
        >
          新規登録
        </li>
      </ul>
      <!-- ログインフォーム -->
      <section class="c-panel" v-show="tab === 1">
        <form class="p-form__auth" @submit.prevent="login">
          <label class="c-form__label" for="login-email">メールアドレス</label>
          <ul v-if="loginErrors">
            <li class="c-error" v-for="msg in loginErrors.email" :key="msg">
              {{ msg }}
            </li>
          </ul>
          <input
            type="text"
            class="c-input c-form__input"
            id="login-email"
            v-model="loginForm.email"
          />

          <label class="c-form__label" for="login-password">パスワード</label>
          <ul v-if="loginErrors">
            <li class="c-error" v-for="msg in loginErrors.password" :key="msg">
              {{ msg }}
            </li>
          </ul>
          <input
            type="password"
            class="c-input c-form__input"
            id="login-password"
            v-model="loginForm.password"
            maxlength="50"
          />

          <!-- remember me -->
          <div class="c-form__info c-form__checkbox">
            <label class="c-form__label" for="remember">
              <input
                type="checkbox"
                name="remember"
                id="remember"
                class="c-form__checkbox--check"
                v-model="loginForm.remember"
              />
              ログイン状態を維持する
            </label>
          </div>

          <div class="form__button">
            <button type="submit" class="c-btn c-btn__auth u-mt-xl">
              ログイン
            </button>
          </div>
        </form>

        <!-- その他 -->
        <div class="p-auth__another">
          <div class="c-border">
            <div class="c-border__dividingText">
              <span class="c-border__dividingText-spanborder">または</span>
            </div>
          </div>

          <section>
            <button class="c-btn" @click="addTestUserForm">TEST USER</button>
          </section>
        </div>
      </section>

      <!-- 新規登録フォーム -->
      <section class="c-panel" v-show="tab === 2">
        <form class="p-form__auth" @submit.prevent="register">
          <label class="c-form__label" for="username"
            >USERNAME (半角英数字 3~32字)</label
          >
          <ul v-if="registerErrors">
            <li class="c-error" v-for="msg in registerErrors.name" :key="msg">
              {{ msg }}
            </li>
          </ul>
          <input
            type="text"
            class="c-input c-form__input"
            id="username"
            v-model="registerForm.name"
            maxlength="32"
            placeholder="半角英数字 3~32字"
          />

          <label class="c-form__label" for="email">メールアドレス</label>
          <ul v-if="registerErrors">
            <li class="c-error" v-for="msg in registerErrors.email" :key="msg">
              {{ msg }}
            </li>
          </ul>
          <input
            type="text"
            class="c-input c-form__input"
            id="email"
            v-model="registerForm.email"
            placeholder="example@mail.com"
          />

          <label class="c-form__label" for="password"
            >パスワード (半角英数字 8~50字)</label
          >
          <ul v-if="registerErrors">
            <li
              class="c-error"
              v-for="msg in registerErrors.password"
              :key="msg"
            >
              {{ msg }}
            </li>
          </ul>
          <input
            type="password"
            class="c-input c-form__input"
            id="password"
            v-model="registerForm.password"
            maxlength="50"
            placeholder="半角英数字 8~50字"
          />
          <label class="c-form__label" for="password-confirmation"
            >パスワードの再入力</label
          >
          <input
            type="password"
            class="c-input c-form__input"
            id="password-confirmation"
            v-model="registerForm.password_confirmation"
            maxlength="50"
            placeholder=""
          />
          <div class="p-form__button">
            <button type="submit" class="c-btn c-btn__auth u-mt-xl">
              新規登録
            </button>
          </div>
        </form>
      </section>
    </div>
  </div>
</template>

<script>
const testUserEmail = 'testuser@example.com';
const testUserPassword = 'testtest';
export default {
  data() {
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
        password_confirmation: '',
      },
    };
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus;
    },
    registerErrors() {
      return this.$store.state.auth.registerErrorMessages;
    },
    loginErrors() {
      return this.$store.state.auth.loginErrorMessages;
    },
  },
  methods: {
    // ======================================
    // 新規会員登録(authストアのregisterアクション)
    // ======================================
    async register() {
      await this.$store.dispatch('auth/register', this.registerForm);
      // ログイン成功時、遷移させる
      if (this.apiStatus) {
        // トップに遷移させる
        this.$router.go({
          path: `/`,
          force: true,
        });
      }
    },
    // =======
    // ログイン
    // =======
    async login() {
      await this.$store.dispatch('auth/login', this.loginForm);
      if (this.apiStatus) {
        // トップに遷移させる
        this.$router.go({
          path: `/`,
          force: true,
        });
      }
    },
    // ====================
    // エラーメッセージのクリア
    // ====================
    clearError() {
      this.$store.commit('auth/setRegisterErrorMessages', null);
      this.$store.commit('auth/setLoginErrorMessages', null);
    },
    // ====================
    // テストユーザー用
    // ====================
    addTestUserForm() {
      this.loginForm.email = testUserEmail;
      this.loginForm.password = testUserPassword;
    },
  },
  created() {
    this.clearError();
  },
};
</script>
