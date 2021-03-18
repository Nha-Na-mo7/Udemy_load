<!--投稿済みレコードの編集-->
<template>
  <div>
    <h2>編集</h2>
    <div>
<!--      <form action="">-->
<!--        <input type="text">-->
<!--        <textarea></textarea>-->
<!--        <div>-->
<!--          <input type="text">-->
<!--          <input type="text">-->
<!--          <textarea></textarea>-->
<!--        </div>-->
<!--      </form>-->
    </div>
  </div>
</template>

<script>
import {OK, FORBIDDEN} from "../../util";

export default {
  props: {
    id: {
      type: String,
      required: true,
    }
  },
  data() {
    return {
      record: {},
    }
  },
  methods: {
    // ========================
    // レコードの情報をDBから取得
    // ========================
    async fetchRecord() {

      console.log('レコード情報を取得しました。')
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
    },
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