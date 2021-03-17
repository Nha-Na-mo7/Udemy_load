<!--=======================================================-->
<!--パスワード変更画面のコンポーネント。登録があるかで新規・更新を分ける-->
<!--=======================================================-->
<template>
  <div class="l-container__setting">
    <SettingItemList />

    <button class="c-btn" @click="toggleTest">toggletest</button>
    <button class="c-btn" @click="toggleLoading">toggleloading</button>

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
    <!-- TODO 認証済みの時しかアクセスできないのでvuexからユーザー情報を取得してリンクに貼っつける -->
    <div class="u-text--center">
      <RouterLink to="/" class="c-btn">マイページへ戻る</RouterLink>
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
    toggleTest() {
      this.isExistPassword = !this.isExistPassword
    },
    toggleLoading() {
      this.isLoading = !this.isLoading
    },
  },
  components: {
    Loading,
    SettingItemList,
    PasswordCreate,
    PasswordUpdate,
  },
};
</script>

<style scoped></style>
