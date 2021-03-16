<template>
  <div class="p-record__list">
    <div class="p-record__list--inner">
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

</style>