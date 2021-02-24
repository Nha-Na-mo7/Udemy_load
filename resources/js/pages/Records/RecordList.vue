<template>
  <div class="f_page">
    <h1>Record List 学習の記録</h1>

    <div>
      <h2>UDEMY講座検索</h2>
      <form action="">
        <label>
          <input type="text" v-model="searchData.keywords">
        </label></form>
      <button
          class="c-btn"
          @click="searchCourse"
      >講座検索
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchWord: '',
      isSearching: false,
      searchData: {
        keywords: '',
      }
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

      this.isSearching = false;
      console.log('メソッド finished!')
    }
  }
}
</script>

<style scoped>
  .f_page {
    font-size: 20px;
    margin-top: 60px;
    height: 320px;
    background: #dffcf2;
  }
</style>