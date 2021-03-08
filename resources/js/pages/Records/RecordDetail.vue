<template>
  <div>
    <h2>レコードの詳細画面です</h2>
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
      record: null,
    }
  },
  methods: {
    // レコードの情報をDBから取得する
    async fetchRecord() {
      console.log('レコード情報を取得しました。')
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