<template>
  <div class="p-container">
    <h2>レコードの詳細画面です</h2>
    <p>ID: {{ this.id }}</p>

    <!-- 詳細 -->
    <div class="p-record__detail">
      <!-- タイトル -->
      <h2 class="p-record__detail--title">{{ this.title }}</h2>
      <!-- Description -->
      <p class="p-record__detail--description">{{ this.description }}</p>
    </div>

  </div>
</template>
<script>

import { OK } from '../../util.js'

export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      record: {},
    }
  },
  computed: {
    title() {
      return this.record.title
    },
    description() {
      return this.record.description
    },
  },
  methods: {
    // レコードの情報をDBから取得
    async fetchRecord() {
      console.log('レコード情報を取得しました。')
      // レコード情報を取得
      const response = await axios.get(`/record/${this.id}`)
      // エラー時の処理
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false
      }
      // 格納
      this.record = response.data
    }
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