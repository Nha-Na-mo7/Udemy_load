<template>
  <div class="p-record">

    <!-- インフォメーション -->
    <div class="p-record__info">
      <div class="p-record__info--inner">
        <!-- タイトル -->
        <h2 class="p-record__info--title">{{ this.title }}</h2>
        <!-- 投稿者 -->
        <RouterLink
            class="p-record__list-item__username"
            :to="`/mypage/${ this.ownerName }`"
        >ユーザー名: {{ this.ownerName }}
        </RouterLink>
        <!-- Description -->
        <p v-html="description" class="p-record__info--description"></p>
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

    <!-- コメント欄 -->
    <div class="p-record__comment">
      <h3>コメント</h3>
      <!-- 投稿一覧 -->
      <div class="p-record__comment--list">

      </div>
      <!-- 投稿フォーム(ログイン必須) -->
      <div v-if="isLogin">
        <h3>投稿する</h3>
        <form class="c-form" @submit.prevent="addComment">
        <textarea
            class="p-record__comment--textarea c-form__textarea"
            v-model="commentContent"
        ></textarea>
          <div class="c-form__button">
            <button class="c-btn">コメントを投稿</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>
<script>

import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util.js'
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
      commentContent: '',
      commentErrors: null
    }
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check']
    },
    ownerName() {
      return this.record.owner.name
    },
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
    },
    // コメントを投稿
    async addComment() {
      const response = await axios.post(`/record/${this.id}/comments`,{
        content: this.commentContent
      })

      // バリデーションエラー
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.commentErrors = response.data.errors
        return false
      }

      // 投稿後テキストエリア、エラーメッセージを空にする
      this.commentContent = ''
      this.commentErrors = null

      // それ以外のエラー
      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }
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