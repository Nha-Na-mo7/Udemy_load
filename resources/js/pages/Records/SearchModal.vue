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
            @click="searchCourse"
        >講座検索
        </button>
      </div>

      <!-- 検索結果一覧 -->
      <div id="courselist" class="p-course__list">
        <!-- 検索中 -->
        <div v-if="isSearching">
          <Loading />
        </div>

        <!-- 結果コンポーネント一覧 -->
        <div v-else>
          <Course
              v-for="Course in responseData"
              :key="Course.id"
              :course="Course"
              @addCourse="addCourseObject"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import Course from './Course.vue';

export default {
  data() {
    return {
      isSearching: false,
      searchWord: '',
      searchData: {
        keywords: '',
      },
      responseData: [],
      selectedCourses: [],
    }
  },
  methods: {
    // コースの検索
    async searchCourse() {
      // 検索中に重複して呼び出せないようにする
      if ( this.isSearching ) {
        return false;
      }
      // 検索開始
      this.isSearching = true;

      // 検索ワードを元にUdemyAPIにリクエストする
      const params = this.searchData;
      const response = await axios.get('/udemy/course/get', { params });

      // 検索結果を取得
      this.responseData = response.data.results;

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
    Course
  }
}

</script>

<style scoped>

</style>