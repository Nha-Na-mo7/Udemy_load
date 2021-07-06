<template>
  <div class="l-container__setting u-flex u-space-between">
    <SettingItemList />

    <!-- 読み込み中 -->
    <div v-if="isLoading">
      <Loading />
    </div>
    <!-- アカウント関連 -->
    <div v-else class="p-setting">
      <div class="p-setting__container">
        <h2 class="p-setting__title">プロフィールの設定</h2>

        <!-- 所属 -->
        <section class="p-setting__item">
          <div class="p-setting__flex">
            <div class="p-setting__input">
              <label class="c-form__label" for="organization">所属企業・組織など</label>
              <ul v-if="errorsOrganization">
                <li class="c-error" v-for="error in errorsOrganization" :key="error">
                  <span>{{ error }}</span>
                </li>
              </ul>
              <input
                  id="organization"
                  class="c-form__input"
                  type="text"
                  maxlength="32"
                  v-model="formProfiles.organization"
              />

              <label class="c-form__label" for="profileText">自己紹介(200文字まで)</label>
              <ul v-if="errorsProfileText">
                <li class="c-error" v-for="error in errorsProfileText" :key="error">
                  <span>{{ error }}</span>
                </li>
              </ul>
              <textarea
                  id="profileText"
                  class="c-form__input"
                  type="text"
                  maxlength="200"
                  rows="10"
                  v-model="formProfiles.profileText"
              />

            </div>
          </div>

          <div class="u-text--center u-mt-xl">
            <button class="c-btn" @click="updateProfile">更新する</button>
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
      formProfiles: {
        // 所属企業・組織
        organization: '',
        // プロフィール本文
        profileText: '',
      },
      systemError: [],
      errorsOrganization: [],
      errorsProfileText: [],
    };
  },
  methods: {
    // ストアからプロフィールを取得
    async getProf() {
      this.formProfiles.organization = this.$store.getters['auth/organization'] ?? ''
      this.formProfiles.profileText = this.$store.getters['auth/profile_text'] ?? ''
      this.isLoading = false;
    },
    // プロフィールの更新
    async updateProfile() {
      if (this.isUpdating) {
        return false;
      }
      this.isUpdating = true;

      // 更新処理
      const response = await axios
          .post(`/user/update/profiles`, { profiles: this.formProfiles })
          .catch((error) => error.response || error);

      // エラーチェック
      switch (response.status) {
        // バリデーションエラー
        case UNPROCESSABLE_ENTITY:
          this.errorsProfileText = response.data.errors.profile;
          this.errorsOrganization = response.data.errors.organization;
          this.isUpdating = false;
          break;
        // テストユーザーなどの時
        case FORBIDDEN:
          this.$router.go({
            path: this.$router.currentRoute.path,
            force: true,
          });
          break;
        // 500エラー
        case INTERNAL_SERVER_ERROR:
          return false
        // エラーがない場合の処理
        default:
          // バリデーションエラーリストを空にする
          this.errorsEmail = [];
          // ページをリロードする
          this.$router.go({
            path: this.$router.currentRoute.path,
            force: true,
          });
      }
      this.isUpdating = false;
    }
  },
  components: {
    Loading,
    SettingItemList,
  },
  watch: {
    $route: {
      async handler() {
        await this.getProf();
      },
      immediate: true,
    },
  },
};
</script>

<style scoped></style>
