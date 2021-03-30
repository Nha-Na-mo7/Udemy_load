<!-- コース検索用モーダルコンポーネント -->
<template>
  <div>
    <div class="c-modal__cover" @click="closeModal"></div>
    <div class="c-modal">
      <!-- コース検索フォーム-->
      <div class="c-modal__searchform">
        <p>コースを選択し、レコードに追加してください</p>
        <form action="">
          <label>
            <input type="text" class="c-input" v-model="searchData.keywords">
          </label></form>
        <button
            class="c-btn"
            @click="searchCourse(0)"
        >講座検索
        </button>
      </div>

      <!-- 検索結果一覧 -->
      <div id="courselist" class="p-course__list c-modal__searchResult">
        <!-- 検索中 -->
        <div v-if="isSearching">
          <Loading />
        </div>

        <!-- 結果コンポーネント一覧 -->
        <div v-else>
          <SearchResultCourse
              v-for="Course in responseData"
              :key="Course.id"
              :course="Course"
              @addCourse="addCourseObject"
          />
        </div>
      </div>

      <!-- 前へ / 次へ -->
      <div>
        <button class="c-btn" v-if="existPrevUrl" @click="searchCourse(1)">前へ</button>
        <button class="c-btn" v-if="existNextUrl" @click="searchCourse(2)">次へ</button>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import SearchResultCourse from './SearchResultCourse.vue';

export default {
  data() {
    return {
      isSearching: false,
      searchWord: '',
      searchData: {
        keywords: '',
      },
      prevUrl: {
        url: ''
      },
      nextUrl: {
        url: ''
      },
      responseData: [],
      selectedCourses: [],
    }
  },
  computed: {
    existPrevUrl() {
      return this.prevUrl.url !== ''
    },
    existNextUrl() {
      return this.nextUrl.url !== ''
    },
  },
  methods: {
    // コースの検索
    async searchCourse(flg) {
      // 検索中に重複して呼び出せないようにする
      if ( this.isSearching ) {
        return false;
      }
      // 検索開始
      this.isSearching = true;

      // 検索ワードを元にUdemyAPIにリクエストする
      const params = flg === 0 ? this.searchData : flg === 1 ? this.prevUrl : this.nextUrl
      const response = await axios.get('/udemy/course/get', { params });

      // 検索結果を取得
      this.responseData = response.data.results;
      this.nextUrl.url = response.data.next ?? '';
      this.prevUrl.url = response.data.previous ?? '';

      // 検索中フラグをfalseに
      this.isSearching = false;
    },
    // 検索をリセット
    resetSearchWord() {
      this.searchWord = ''
      this.searchData.keywords = ''
      this.responseData = []
    },
    // オブジェクトを配列に追加した上でモーダルを閉じる
    addCourseObject(e) {
      this.$emit('pushCourseObjToSelectedCoursesArr', e)
      this.closeModal()
    },
    // モーダルを閉じる処理
    closeModal() {
      this.resetSearchWord()
      this.$emit('toggleModal')
    },
  },
  components: {
    Loading,
    SearchResultCourse
  }
}

</script>

<style scoped>

</style>