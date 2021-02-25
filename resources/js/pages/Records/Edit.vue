<template>
  <div class="f_page">
    <h1>講座登録</h1>
    <div>
      <p>コースを選択し、レコードに追加してください</p>
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

</style>