<template>
  <div class="f_page">
    <h1>Record List 学習の記録</h1>
    <div class="f_records__list--container">
      <Record
          v-for="Record in records"
          :key="Record.id"
          :item="Record"
      />
    </div>
  </div>
</template>

<script>
import { OK } from '../../util.js'
import Record from "./Record";
export default {
  data() {
    return {
      records: [],
      currentPage: 0,
      lastPage: 0,
    }
  },
  methods: {
    // コースレコード一覧の取得
    async fetchCourse() {
      const response = await axios.get(`/records/index`);

      // エラー時
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      console.log(response)
      this.records = response.data.data
    }
  },
  components: {
    Record
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchCourse()
      },
      immediate: true
    }
  }
}
</script>

<style scoped>
  .f_page {
    font-size: 20px;
    margin-top: 120px;
    height: 320px;
    background: #dffcf2;
  }
  .f_records__list--container {
    padding: 12px;
    background: #deecec;
    border: 1px solid #000;
  }
</style>