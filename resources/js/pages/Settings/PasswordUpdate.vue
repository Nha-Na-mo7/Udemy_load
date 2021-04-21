<!--===============-->
<!-- パスワード更新用 -->
<!--===============-->
<template>
  <div class="p-setting__container">
    <h2 class="p-setting__title">パスワードの更新</h2>

    <section class="p-setting__item p-form">
      <div class="p-form__description">
        <p>※ 他のサービスと同じパスワードは使用しないでください</p>
      </div>

      <label class="c-form__label" for="old_password">現在のパスワード</label>

      <ul v-if="errorsOldPassword">
        <li
            class="c-error"
            v-for="error in errorsOldPassword"
            :key="error"
        >
          <span>{{ error }}</span>
        </li>
      </ul>
      <input
          id="old_password"
          class="c-form__input"
          type="password"
          v-model="formPassword.old_password"
      />

      <label class="c-form__label" for="password"
      >新しいパスワード (半角英数字 8~50文字)</label
      >

      <ul v-if="errorsPassword">
        <li
            class="c-error"
            v-for="error in errorsPassword"
            :key="error"
        >
          <span>{{ error }}</span>
        </li>
      </ul>
      <input
          id="password"
          class="c-form__input"
          type="password"
          v-model="formPassword.password"
      />

      <label class="c-form__label" for="password_confirmation"
      >新しいパスワード【再入力】</label
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
          class="c-form__input"
          type="password"
          v-model="formPassword.password_confirmation"
      />

      <div class="c-form__submit u-text--center">
        <button class="c-btn c-btn__setting--update" @click="updatePassword">
          <i class="fas fa-key"></i> 変更する
        </button>
      </div>
    </section>
  </div>
</template>

<script>
import { UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR } from '../../util.js';

export default {
  data() {
    return {
      isUpdating: false,

      errorsOldPassword: '',
      errorsPassword: '',
      errorsPasswordConfirmation: '',
      formPassword: {
        old_password: '',
        password: '',
        password_confirmation: '',
      },
    };
  },
  methods: {
    // パスワードの更新
    async updatePassword() {
      // 更新処理中は複数回起動できないようにする
      if (this.isUpdating) {
        return false;
      }
      console.log('パスワードの更新処理です')
      this.isUpdating = true;

      // 更新処理にアクセス
      const response = await axios
          .post(`/user/update/password`, this.formPassword)
          .catch((error) => error.response || error);

      // エラーチェック
      if (response.status === UNPROCESSABLE_ENTITY) {
        // バリデーションエラー。帰ってきたエラーメッセージを格納
        this.errorsOldPassword = response.data.errors.old_password;
        this.errorsPassword = response.data.errors.password;
        this.errorsPasswordConfirmation =
            response.data.errors.password_confirmation;

        // 500エラーの時
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        console.log('500 ERROR')
        // // フラッシュメッセージをセット
        // this.$store.commit('message/setContentError', {
        //   content: response.data.errors,
        // });
        // 成功時
      } else {
        console.log('PASSWORD UPDATE SUCCESS!!!')
        // // フラッシュメッセージをセット
        // this.$store.commit('message/setContentSuccess', {
        //   content: response.data.success,
        // });
        // パスワード更新完了後はマイページに戻す
        this.$router.push(`/mypage/${this.$store.getters['auth/username']}`);
      }
      this.isUpdating = false;
    },
  },
};
</script>

<style scoped></style>
