<!--==========-->
<!--検索用ページ-->
<!--==========-->
<template>
  <div class="p-search">
    <div class="p-search__container">
      <form action="" class="p-search__form c-form">
        <input
            type="text"
            class="c-form__input c-form__input--recordSearch"
        >
        <button
            class="p-search__btn c-btn"
            @click=""
        >
          <i class="fas fa-search c-icon__fa--default"></i> 検索
        </button>
      </form>

      <!-- 検索結果一覧 -->
      <div id="searchResult" class="p-search__result--container">
        <div v-if="isSearching">
          <Loading />
        </div>

        <!-- エラー時 -->
        <div v-else-if="errors" class="c-modal__error">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p class="c-error">{{ errors }}</p>
        </div>

        <!-- pagination -->
        <div>
          <h2>ここにpaginationが入ります</h2>
          <h2>全部で 10000000 の検索結果</h2>
        </div>

        <!-- 結果コンポーネント一覧 -->
        <Result
            v-for="Record in records"
            :key="Record.id"
            :item="Record"
        />

        <!-- 検索結果がない場合 -->
        <div class="c-modal__nothing">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p>「xxxxxx」と一致するロードマップは見つかりませんでした。</p>
          <p>検索ワードを入れてください。</p>
        </div>

        <!-- pagination -->
        <div>
          <h2>ここにpaginationが入ります</h2>
          <button class="c-btn">前へ</button>
          <button class="c-btn">次へ</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Loading from '../../components/Loading.vue';
import Result from './Result.vue';
import {OK} from "../../util";

export default {
  data() {
    return {
      isSearching: false,
      errors: '',
      records: []
    }
  },
  methods: {
    // ロードマップ一覧の取得
    async fetchCourse() {
      const response = await axios.get(`/records/index`);

      // エラー時
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.records = response.data;
    },
  },
  components: {
    Loading,
    Result,
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchCourse()
        const num = Math.random() * 10
        console.log(num)
        const title = num > 5 ? '検索' : '何も入っていません'
        document.title = `${title} |  UdemyLoad`
      },
      immediate: true,
    },
  },
}
</script>
<style scoped>
</style>