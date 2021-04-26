<!-- コース検索用モーダルコンポーネント -->
<template>
  <div>
    <div class="c-modal__cover" @click="closeModal"></div>
    <div class="p-modal c-modal">
      <!-- コース検索フォーム-->
      <div class="p-modal__search__form c-modal__searchform">
        <p class="p-modal__search__title c-modal__title u-mb-l">
          コースを検索してください
        </p>
        <form class="" action="" onsubmit="return false">
          <label>
            <input
              type="text"
              class="c-form__input"
              v-model="searchData.keywords"
            />
          </label>
        </form>
        <div class="p-modal__search__btn-inner u-mb-l">
          <button class="c-btn c-btn__modal" @click="searchCourse(0)">
            <i class="fas fa-search"></i> 検索
          </button>
          <button class="c-btn c-btn__modal" @click="resetSearchWord">
            リセット
          </button>
        </div>
      </div>

      <!-- 検索結果一覧 -->
      <div id="courselist" class="p-course__list c-modal__searchResult">
        <!-- 検索中 -->
        <div v-if="isSearching">
          <Loading />
        </div>

        <!-- エラーが発生した時 -->
        <div v-else-if="errors" class="c-modal__error">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p class="c-error">{{ errors }}</p>
        </div>

        <!-- 結果コンポーネント一覧 -->
        <div v-else-if="isExistResult">
          <SearchResultCourse
            v-for="Course in responseData"
            :key="Course.id"
            :course="Course"
            @addCourse="addCourseObject"
          />
        </div>

        <!-- 未検索時 -->
        <div v-else-if="isNotSearchedYet" class="u-none"></div>

        <!-- 検索結果がない場合 -->
        <div v-else class="c-modal__nothing">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <h3>コースが見つかりませんでした</h3>
        </div>
      </div>

      <!-- 前へ / 次へ -->
      <div class="p-modal__search__btn-inner u-mt-l">
        <button
          class="c-btn c-btn__modal"
          v-if="existPrevUrl"
          @click="searchCourse(1)"
        >
          前へ
        </button>
        <button
          class="c-btn c-btn__modal"
          v-if="existNextUrl"
          @click="searchCourse(2)"
        >
          次へ
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import SearchResultCourse from './SearchResultCourse.vue';
import { INTERNAL_SERVER_ERROR } from '../../util';

export default {
  data() {
    return {
      isSearching: false,
      isNotSearchedYet: true,
      searchWord: '',
      searchData: {
        keywords: '',
      },
      prevUrl: {
        url: '',
      },
      nextUrl: {
        url: '',
      },
      responseData: [],
      selectedCourses: [],
      errors: '',
    };
  },
  computed: {
    // 検索した結果が存在するか
    isExistResult() {
      return !!this.responseData.length;
    },
    existPrevUrl() {
      return this.prevUrl.url !== '';
    },
    existNextUrl() {
      return this.nextUrl.url !== '';
    },
  },
  methods: {
    // コースの検索
    async searchCourse(flg) {
      // 検索中に重複して呼び出せないようにする
      if (this.isSearching) {
        return false;
      }
      // 検索開始
      this.isSearching = true;
      this.isNotSearchedYet = false;

      // エラーをリセット
      this.errors = '';

      // 検索ワードを元にUdemyAPIにリクエストする
      const params =
        flg === 0 ? this.searchData : flg === 1 ? this.prevUrl : this.nextUrl;
      const response = await axios.get('/udemy/course/get', { params });

      // エラー時処理(UdemyAPIのエラーになる)
      if (response.status === INTERNAL_SERVER_ERROR) {
        this.errors =
          'Udemyコース検索でエラーが発生しました。しばらく時間を置いてからやり直してください。';
        this.isSearching = false;
        return false;
      }

      // 検索結果を取得
      this.responseData = response.data.results;
      this.nextUrl.url = response.data.next ?? '';
      this.prevUrl.url = response.data.previous ?? '';

      // 検索中フラグをfalseに
      this.isSearching = false;
    },
    // 検索ワードを空に
    resetSearchWord() {
      this.searchWord = '';
      this.searchData.keywords = '';
    },
    // 検索をリセット
    resetSearch() {
      this.resetSearchWord();
      this.responseData = [];
    },
    // オブジェクトを配列に追加した上でモーダルを閉じる
    addCourseObject(e) {
      this.$emit('pushCourseObjToSelectedCoursesArr', e);
      this.closeModal();
    },
    // モーダルを閉じる処理
    closeModal() {
      this.resetSearch();
      this.$emit('toggleModal');
    },
  },
  components: {
    Loading,
    SearchResultCourse,
  },
};
</script>

<style scoped></style>
