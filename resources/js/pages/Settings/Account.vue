<template>
  <div class="l-container__setting u-flex u-space-between">
    <!-- リスト -->
    <SettingItemList />

    <!-- 読み込み中 -->
    <div v-if="isLoading">
      <Loading />
    </div>

    <!-- アカウント関連 -->
    <div v-else class="p-setting">
      <div class="p-setting__container">
        <h2 class="p-setting__title">アカウント設定</h2>

        <!-- ユーザーネームの更新 -->
        <section class="p-setting__item p-setting__updatename">
          <h3 class="p-setting__title p-setting__title--sub">
            ユーザー名の変更
          </h3>
          <div class="p-setting__flex">
            <div class="p-setting__input">
              <label class="c-form__label" for="name"
                >USERNAME (半角英数字 3~32文字)</label
              >
              <ul v-if="errorsName">
                <li class="c-error" v-for="error in errorsName">
                  <span>{{ error }}</span>
                </li>
              </ul>
              <input
                id="name"
                class="c-form__input"
                type="text"
                v-model="formName"
                maxlength="32"
                placeholder="半角英数字 3~32字"
              />
            </div>
            <div class="p-setting__btn">
              <button class="c-btn c-btn__setting--update" @click="updateName">
                変更
              </button>
            </div>
          </div>
        </section>

        <!-- メールアドレスの更新 -->
        <section class="p-setting__item p-setting__updatemail">
          <h3 class="p-setting__title p-setting__title--sub">
            <i class="far fa-envelope"></i>メールアドレスの変更
          </h3>
          <div class="p-setting__flex">
            <div class="p-setting__input">
              <label class="c-form__label" for="email">メールアドレス </label>
              <ul v-if="errorsEmail">
                <li class="c-error" v-for="error in errorsEmail">
                  <span>{{ error }}</span>
                </li>
              </ul>
              <input
                id="email"
                class="c-form__input"
                type="text"
                v-model="formEmail"
                maxlength="100"
                placeholder="example@mail.com"
              />
            </div>
            <div class="p-setting__btn">
              <button class="c-btn c-btn__setting--update" @click="updateEmail">
                変更
              </button>
            </div>
          </div>
        </section>

        <!-- アカウントの削除 -->
        <section class="p-setting__item p-setting__withdraw">
          <h3 class="p-setting__title p-setting__title--sub">
            アカウントを削除する
          </h3>
          <p class="u-mb-l u-text--center u-color--red">
            【!】アカウントを削除すると元に戻すことはできなくなります。
          </p>
          <div class="u-text--center">
            <button class="c-btn c-btn__setting--withdraw" @click="withdraw">
              <i class="fas fa-sign-out-alt"></i>
              アカウント削除
            </button>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import {
  OK,
  UNPROCESSABLE_ENTITY,
  INTERNAL_SERVER_ERROR,
  FORBIDDEN,
} from '../../util.js';
import SettingItemList from './SettingItemList.vue';

export default {
  data() {
    return {
      isLoading: true,
      isUpdating: false,
      // ユーザーネームのフォーム
      formName: '',
      // メールアドレスのフォーム
      formEmail: '',
      systemError: [],
      errorsName: [],
      errorsEmail: [],
    };
  },
  methods: {
    // ログイン中のユーザーデータを取得する
    async getUser() {
      const response = await axios
        .get(`/user/info`)
        .catch((error) => error.response || error);

      // エラーチェック
      if (response.status === OK) {
        // ユーザーネームをformNameに格納
        if (response.data.name !== null) {
          this.formName = response.data.name;
        }
        // メールアドレスをformEmailに格納
        if (response.data.email !== null) {
          this.formEmail = response.data.email;
        }
        this.isLoading = false;
      }
    },
    // ユーザーネームの変更
    async updateName() {
      // 更新処理中は複数回起動できないようにする
      if (this.isUpdating) {
        return false;
      }
      this.isUpdating = true;

      // 更新処理にアクセス
      const response = await axios
        .post(`/user/update/name`, { name: this.formName })
        .catch((error) => error.response || error);

      // エラーチェック
      if (response.status === UNPROCESSABLE_ENTITY) {
        // バリデーションエラー
        this.errorsName = response.data.errors.name;
        this.isUpdating = false;
      // テストユーザーなどで403が帰ってきた時
      } else if (response.status === FORBIDDEN) {
        this.$router.go({
          path: this.$router.currentRoute.path,
          force: true,
        });
      // 500エラー時
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        return false;
      } else {
        // 更新成功したらエラーメッセージは空にする
        this.errorsName = [];
        // ページをリロードする
        this.$router.go({
          path: this.$router.currentRoute.path,
          force: true,
        });
      }
      this.isUpdating = false;
    },
    // メールアドレスの変更
    async updateEmail() {
      // 更新処理中は複数回起動できないようにする
      if (this.isUpdating) {
        return false;
      }
      this.isUpdating = true;

      // 更新処理にアクセス
      const response = await axios
        .post(`/user/update/email`, { email: this.formEmail })
        .catch((error) => error.response || error);

      // エラーチェック
      if (response.status === UNPROCESSABLE_ENTITY) {
        // バリデーションエラー時
        this.errorsEmail = response.data.errors.email;
        this.isUpdating = false;
        // テストユーザーなどで403が帰ってきた時
      } else if (response.status === FORBIDDEN) {
        this.$router.go({
          path: this.$router.currentRoute.path,
          force: true,
        });
        // 500エラー時
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        return false;
      } else {
        // バリデーションエラーリストを空にする
        this.errorsEmail = [];
        // ページをリロードする
        this.$router.go({
          path: this.$router.currentRoute.path,
          force: true,
        });
      }
      this.isUpdating = false;
    },
    // アカウント削除処理 PHP側でデータ削除して、フロント側で画面遷移させる。
    async withdraw() {
      if (
        confirm(
          '【アカウントの削除】\nアカウントを削除した場合、元に戻すことはできません。よろしいですか？',
        )
      ) {
        const response = await axios.post(`/withdraw`);
        if (response.status === OK) {
          window.location = '/';
        } else {
          window.location = '/login';
        }
      }
    },
  },
  components: {
    Loading,
    SettingItemList,
  },
  watch: {
    $route: {
      async handler() {
        // ページの読み込み直後にユーザーの取得を行う
        await this.getUser();
      },
      immediate: true,
    },
  },
};
</script>

<style scoped></style>
