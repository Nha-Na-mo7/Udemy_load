<template>
  <div class="p-record">

    <div class="p-record__info">
      <div class="p-record__info--inner">
        <!-- タイトル -->
        <h2 class="p-record__info--title">{{ this.title }}</h2>
        <!-- Description -->
        <p class="p-record__info--description">{{ this.description }}</p>
      </div>
    </div>

    <!-- 詳細 -->
    <div class="p-record__detail">
      <!-- コースコンポーネント -->
      <div class="p-record__detail--list">
        <!-- TODO INDEXによる並び替えをサーバサイドで行う処理を書いてください-->
        <CourseDetail
          v-for="Course in this.record.courses"
          :key="Course.id"
          :course="Course"
        />
      </div>
    </div>

  </div>
</template>
<script>

import { OK } from '../../util.js'
import CourseDetail from './CourseDetail'

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
  components: {
    CourseDetail
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