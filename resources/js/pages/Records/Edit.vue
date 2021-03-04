<template>
  <div class="f_page">
    <h1>講座登録</h1>

    <!-- 現在追加されているコース-->
    <div>
      <p>{{ selectedCourses }}</p>
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
      <div>
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
      searchWord: '',
      modalFlg: false,
      isSearching: false,
      searchData: {
        keywords: '',
      },
      responseData: [],
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
    // TODO 配列追加検証用の仮メソッド
    plusarr(e) {
      this.selectedCourses.push(e);
    },
    // オブジェクトを配列に追加する一連の流れ
    addCourseObject(e) {
      this.plusarr(e)
      this.resetSearchWord()
      this.toggleModalFlg()
    }
  },
  components: {
    Loading,
    Course,
  }
}

</script>

<style scoped>

</style>