<!--=======================================================-->
<!--パスワード変更画面のコンポーネント。登録があるかで新規・更新を分ける-->
<!--=======================================================-->
<template>
  <div class="l-container__setting">
    <!-- リスト -->
    <SettingItemList />

    <!-- 読み込み中 -->
    <div v-if="isLoading">
      <Loading />
    </div>

    <!-- パスワード変更フォーム -->
    <div v-else>
      <!-- パスワードが設定済みの時 -->
      <div v-if="isExistPassword">
        <PasswordUpdate />
      </div>

      <!-- パスワードがまだ未登録の時 -->
      <div v-else>
        <PasswordCreate />
      </div>
    </div>

    <!-- 戻るボタン -->
    <div class="u-text--center">
      <RouterLink
          :to="`/mypage/${this.$store.getters['auth/username']}`"
          class="c-btn"
      >マイページへ戻る</RouterLink>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import SettingItemList from "./SettingItemList.vue";
import PasswordCreate from './PasswordCreate.vue';
import PasswordUpdate from './PasswordUpdate.vue';
import { OK } from '../../util.js';

export default {
  data() {
    return {
      isLoading: true,
      isExistPassword: false,
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
        // パスワードが既に設定されている場合、isExistPasswordをtrueとする
        if (response.data.password !== null) {
          this.isExistPassword = true;
        }
        this.isLoading = false;
      }
    },
  },
  components: {
    Loading,
    SettingItemList,
    PasswordCreate,
    PasswordUpdate,
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
