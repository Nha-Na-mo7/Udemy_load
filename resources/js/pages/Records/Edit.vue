<template>
  <div class="f_page">
    <h2>講座登録</h2>

    <!-- 現在追加されているコース-->
    <div>
      <SelectedCourse
        v-for="(Course, index) in selectedCourses"
        :key="Course.id"
        :course="Course"
        @deleteCourse="deleteCourseObject(index)"
      />
    </div>

    <!-- コースを追加する -->
    <div>
      <button class="c-btn" @click="toggleModalFlg">
        コースを追加する
      </button>
    </div>

    <!-- モーダルがONになったら表示される -->
    <!-- TODO コンポーネントわけすること -->
    <div v-if="modalFlg">
      <SearchModal
          @pushCourseObjToSelectedCoursesArr="pushCourseObjToSelectedCoursesArr"
          @toggleModal="toggleModalFlg"
      />
    </div>

    <!-- 投稿する -->
    <div>
      <button class="c-btn">投稿する</button>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import SelectedCourse from './SelectedCourse.vue';
import SearchModal from './SearchModal.vue';

export default {
  data() {
    return {
      modalFlg: false,
      selectedCourses: [],
    }
  },
  methods: {
    async searchCourse() {
      // 検索中に重複して呼び出せないようにする
      if ( this.isSearching ) {
        return false;
      }
      console.log('search START!!!')
      // 検索開始
      this.isSearching = true;

      // 検索ワードを元にUdemyAPIにリクエストする
      const params = this.searchData;
      const response = await axios.get('/udemy/course/get', { params });

      console.log(response)
      this.responseData = response.data.results;

      this.isSearching = false;
      console.log('メソッド finished!')
    },
    // 検索をリセット
    resetSearchWord() {
      this.searchWord = ''
      this.searchData.keywords = ''
      this.responseData = []
    },
    // モーダルフラグを切り替え
    toggleModalFlg() {
      this.modalFlg = !this.modalFlg;
    },
    // 選択済みコースに追加する
    pushCourseObjToSelectedCoursesArr(e) {
      this.selectedCourses.push(e);
    },
    // オブジェクトを配列に追加する一連の流れ
    addCourseObject(e) {
      this.pushCourseObjToSelectedCoursesArr(e)
      this.resetSearchWord()
      this.toggleModalFlg()
    },
    // オブジェクトを削除する
    deleteCourseObject(index) {
      this.selectedCourses.splice(index, 1)
    },
  },
  components: {
    Loading,
    SelectedCourse,
    SearchModal
  }
}

</script>

<style scoped>

</style>