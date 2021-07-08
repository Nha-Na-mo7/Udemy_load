<!--==========-->
<!--検索用ページ-->
<!--==========-->
<template>
  <div class="p-search">
    <div class="p-search__container">
      <form action="" class="p-search__form c-form">
        <input
            type="text"
            class="c-form__input"
        >
        <button
            class="p-search__btn c-btn"
            @click=""
        >
          <i class="fas fa-search c-icon__fa--default"></i> 検索
        </button>
      </form>

      <!-- 検索結果一覧 -->
      <div id="searchResult" class="p-search__result">
        <div v-if="isSearching">
          <Loading />
        </div>

        <!-- エラー時 -->
        <div v-else-if="errors" class="c-modal__error">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p class="c-error">{{ errors }}</p>
        </div>

        <!-- 結果コンポーネント一覧 -->
<!--        <div v-else-if="isExistResult">-->
<!--          <SearchResultCourse-->
<!--              v-for="Course in responseData"-->
<!--              :key="Course.id"-->
<!--              :course="Course"-->
<!--              @addCourse="addCourseObject"-->
<!--          />-->
<!--        </div>-->

        <!-- 検索結果がない場合 -->
        <div v-else class="c-modal__nothing">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p>「xxxxxx」と一致するロードマップは見つかりませんでした。</p>
          <p>検索ワードを入れてください。</p>
        </div>

        <!-- pagination -->
        <div>
          <h2>ここに}paginationが入ります</h2>
          <button class="c-btn">前へ</button>
          <button class="c-btn">次へ</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Loading from '../../components/Loading.vue';

export default {
  data() {
    return {
      isSearching: false,
      errors: ''
    }
  },
  components: {
    Loading
  },
  watch: {
    $route: {
      async handler() {
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