<!--投稿済みレコードの編集-->
<template>
  <div>
    <div v-if="loading">
      <Loading />
    </div>

    <div v-else>
      <h2>編集</h2>
      <div>
      </div>
    </div>

  </div>
</template>

<script>
import {OK, FORBIDDEN} from "../../util";
import Loading from "../../components/Loading"

export default {
  props: {
    id: {
      type: String,
      required: true,
    }
  },
  data() {
    return {
      loading: true,
      record: {},
    }
  },
  methods: {
    // ========================
    // レコードの情報をDBから取得
    // ========================
    async fetchRecord() {
      // レコード情報を取得
      const response = await axios.get(`/record/${this.id}/${true}`)

      // オーナー以外が閲覧した場合(403)、詳細画面に戻す
      if (response.status === FORBIDDEN) {
        this.$router.push(`/records/${this.id}`);
        return false
      }

      // エラー時の処理
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false
      }
      console.log(response.data)
      // 格納
      this.record = response.data
      this.loading = false
    },
  },
  components: {
    Loading,
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchRecord()
      },
      immediate: true
    }
  }
}
</script>

<style scoped>

</style>