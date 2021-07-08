<!--======================================-->
<!-- パスワード新規作成用(SNSから新規登録した人) -->
<!--======================================-->
<template>
  <div class="p-setting__container">
    <h2 class="p-setting__title">パスワード作成</h2>

    <section class="p-setting__item p-form">
      <div class="p-form__description">
        <p>※ 他のサービスと同じパスワードは使用しないでください</p>
      </div>

      <label class="c-form__label" for="password"
        >新しいパスワード(半角英数字 8~50文字)</label
      >

      <ul v-if="errorsPassword">
        <li class="c-error" v-for="error in errorsPassword" :key="error">
          <span>{{ error }}</span>
        </li>
      </ul>
      <input
        id="password"
        class="c-form__input u-mb-xl"
        type="password"
        v-model="formPassword.password"
      />

      <label class="c-form__label" for="password_confirmation"
        >パスワード【再入力】</label
      >

      <ul v-if="errorsPasswordConfirmation">
        <li
          class="c-error"
          v-for="error in errorsPasswordConfirmation"
          :key="error"
        >
          <span>{{ error }}</span>
        </li>
      </ul>
      <input
        id="password_confirmation"
        class="c-form__input u-mb-xl"
        type="password"
        v-model="formPassword.password_confirmation"
      />

      <div class="c-form__submit u-text--center">
        <button class="c-btn c-btn__setting--update" @click="createPassword">
          <i class="fas fa-key"></i> 登録する
        </button>
      </div>
    </section>
  </div>
</template>

<script>
import {
  UNPROCESSABLE_ENTITY,
  INTERNAL_SERVER_ERROR,
  FORBIDDEN,
} from '../../util.js';

export default {
  data() {
    return {
      isUpdating: false,
      errorsPassword: '',
      errorsPasswordConfirmation: '',
      formPassword: {
        password: '',
        password_confirmation: '',
      },
    };
  },
  methods: {
    // パスワードの新規登録
    async createPassword() {
      // 更新処理中は複数回起動できないようにする
      if (this.isUpdating) {
        return false;
      }
      this.isUpdating = true;

      // 更新処理にアクセス
      const response = await axios
        .post(`/user/create/password`, this.formPassword)
        .catch((error) => error.response || error);

      // エラーチェック
      if (response.status === UNPROCESSABLE_ENTITY) {
        // バリデーションエラー。帰ってきたエラーメッセージを格納
        this.errorsPassword = response.data.errors.password;
        this.errorsPasswordConfirmation =
          response.data.errors.password_confirmation;
        // テストユーザーなどで403が帰ってきた時はリロード
      } else if (response.status === FORBIDDEN) {
        this.$router.go({
          path: this.$router.currentRoute.path,
          force: true,
        });
        // 500エラーの時は更新失敗
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        this.$store.commit('error/setCode', response.status);
        return false;
        // 成功時
      } else {
        // パスワード作成完了後はマイページに戻す
        this.$router.push(`/mypage/${this.$store.getters['auth/username']}`);
      }
      this.isUpdating = false;
    },
  },
};
</script>

<style scoped></style>
