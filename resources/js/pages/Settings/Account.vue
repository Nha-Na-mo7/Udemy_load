<template>
  <div class="l-container__setting">
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
        <section class="p-setting__updatename">
          <h3 class="p-setting__title p-setting__title--sub">ユーザー名の更新</h3>
          <input class="c-form__input" type="text" v-model:value="username" maxlength="16">
        </section>

        <!-- アカウントの削除 -->
        <section class="p-setting__withdraw">
          <h3 class="p-setting__title p-setting__title--sub">
            アカウントを削除する
          </h3>
          <p>アカウントを削除すると元に戻すことはできなくなります。</p>
          <div class="u-text--center">
            <button class="c-btn" @click="withdraw">
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
import { OK } from "../../util.js"
import SettingItemList from "./SettingItemList.vue";

export default {
  data() {
    return {
      isLoading: true,
      username: '',
    }
  },
  methods: {
    // ログイン中のユーザーデータを取得する
    async getUser() {
      const response = await axios
          .get(`/user/info`)
          .catch((error) => error.response || error);

      // エラーチェック
      if (response.status === OK) {
        // ユーザーネームをusernameに格納
        console.log(response.data)
        if (response.data.name !== null) {
          this.username = response.data.name;
        }
        this.isLoading = false;
      }
    },
    // 退会処理 PHP側でデータ削除して、フロント側で画面遷移させる。
    async withdraw() {
      if (confirm('【 退会しますか？ 】\n退会すると各種サービスのご利用ができなくなります。',)){
        // TODO テストユーザーの場合は退会処理を行わない
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
    SettingItemList
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
}
</script>

<style scoped>

</style>