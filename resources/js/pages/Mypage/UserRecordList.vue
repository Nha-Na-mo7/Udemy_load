<!-- マイページで表示させる、投稿済みのレコード一覧用コンポーネント -->
<template>
  <div class="p-record__list">

    <h2>{{ userName }}さんの投稿</h2>

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
import Record from "../Records/Record";
export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      records: [],
      currentPage: 0,
      lastPage: 0,
    }
  },
  computed: {
    userName() {
      return this.user.name
    },
    userId() {
      return this.user.id
    }
  },
  methods: {
    // マイページユーザーが投稿したレコード一覧の取得
    async fetchCourse() {
      const response = await axios.get(`/records/index/${this.userId}`);

      // エラー時
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
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