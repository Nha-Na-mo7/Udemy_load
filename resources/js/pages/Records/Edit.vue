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

    // 検索結果
    <div v-if="!!responseData">
      <Course
          v-for="Course in responseData"
          :key="Course.id"
          :course="Course"
      />
    </div>
  </div>
</template>

<script>
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
    Course,
  }
}

</script>

<style scoped>

</style>