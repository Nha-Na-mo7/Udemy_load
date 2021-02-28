<template>
  <div class="f_page">
    <h1>講座登録</h1>
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

    <!-- 追加するボタンが押されたら一度検索窓はリセットされる-->

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
        />
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