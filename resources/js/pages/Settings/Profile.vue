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
                  v-model="formOrganization"
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
                  v-model="formProfileText"
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
      isLoading: false,
      isUpdating: false,
      // 所属企業・組織
      formOrganization: '',
      // プロフィール本文
      formProfileText: '',
      systemError: [],
      errorsOrganization: [],
      errorsProfileText: [],
    };
  },
  methods: {
    // ログイン中のユーザーのプロフィールを取得
    async getProf() {
      const response = await axios
          .get(`/user/info`)
          .catch((error) => error.response || error);

    },

    updateProfile() {
      alert('ここで更新処理をします')
    }
  },
  components: {
    Loading,
    SettingItemList,
  },
  watch: {
    $route: {
      async handler() {
        // ページ読み込み直後にプロフィール取得
        await this.getProf();
      },
      immediate: true,
    },
  },
};
</script>

<style scoped></style>
