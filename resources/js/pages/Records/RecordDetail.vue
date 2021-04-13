<template>
  <div class="p-record u-pb-50">

    <div v-if="loading">
      <Loading />
    </div>

    <div v-else>
      <!-- インフォメーション -->
      <section class="p-record__info">
        <div class="p-record__info--inner">
          <div class="p-record__info--column">

            <!-- 投稿/更新時刻 -->
            <p class="p-record__info--date">投稿時刻: {{ this.createdAt | recordAt }}</p>
            <p class="p-record__info--date" v-if="checkUpdated">最終更新: {{ this.updatedAt | recordAt }}</p>

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

          <!-- 編集ボタン/投稿者自身の場合のみ-->
          <div class="p-record__info--column">
            <RouterLink
                v-if="isOwner"
                class=""
                :to="`/records/${this.id}/edit`">
              <i class="fas fa-pencil-alt c-icon__fa"></i>
            </RouterLink>
          </div>
        </div>



      </section>

      <!-- 詳細 -->
      <section class="p-record__detail">
        <!-- バーの配置用エリア -->
        <div class="p-record__detail--left">
<!--          <span class="p-record__detail&#45;&#45;line"></span>-->
        </div>

        <!-- コースコンポーネント -->
        <div class="p-record__detail--right">
          <div class="p-record__detail--list">
            <CourseDetail
                v-for="(Course, index) in this.record.courses"
                :key="Course.id"
                :course="Course"
                :index="index"
            />
          </div>
        </div>
      </section>

      <!-- コメント欄 -->
      <section class="p-record__comment">
        <h3 class="p-record__comment--head">コメント{{ countComments | addBrackets }}</h3>

        <!-- 一覧 -->
        <ul
            v-if="existComments"
            class="p-record__comment--list"
        >
          <li
              v-for="(Comment, index) in record.comments"
              :key="Comment.content"
              class="p-record__comment--item"
          >
            <p>{{ index + 1 | addBrackets}}</p>
            <p class="p-record__comment--author">{{ Comment.author.name }} さんが{{ Comment.created_at }}に投稿</p>
            <p class="p-record__comment--content">{{ Comment.content }}</p>
          </li>
        </ul>

        <!-- コメントがない時 -->
        <div v-else>
          <h3 class="p-record__comment--item">コメントはありません</h3>
        </div>

        <!-- 投稿フォーム(ログイン必須) -->
        <div v-if="isLogin" class="">
          <form class="p-record__comment--form c-form" @submit.prevent="addComment">
        <textarea
            class="p-record__comment--textarea c-form__textarea u-mb-xl"
            v-model="commentContent"
        ></textarea>
            <div class="c-form__button">
              <button class="c-btn c-btn__edit--submit">コメントを投稿</button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</template>
<script>

import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util.js'
import Loading from '../../components/Loading'
import CourseDetail from './CourseDetail'
import moment from "moment";

export default {
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      loading: true,
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
    // コメント数
    countComments() {
      return this.record.comments.length ?? 0
    },
    existComments() {
      return this.record.comments.length > 0
    },
    // 投稿者と自分が同一か
    isOwner() {
      return this.ownerName === this.$store.getters['auth/username']
    },
    // 投稿時刻
    createdAt() {
      return this.record.created_at
    },
    // 更新時刻
    updatedAt() {
      return this.record.updated_at
    },
    // 更新されているか(投稿時刻と更新時刻が同一ではないか)
    checkUpdated() {
      return this.createdAt < this.updatedAt
    },
  },
  methods: {
    // ========================
    // レコードの情報をDBから取得
    // ========================
    async fetchRecord() {
      console.log('レコード情報を取得しました。')
      // レコード情報を取得
      const response = await axios.get(`/record/${this.id}`)
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
    // ================
    // コメントを投稿
    // ================
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

      // ページをリロードする
      this.$router.go({
        path: this.$router.currentRoute.path,
        force: true
      })
    }
  },
  components: {
    Loading,
    CourseDetail,
  },
  filters: {
    addBrackets(count) {
      return '(' + count + ')'
    },
    recordAt(date) {
      return moment(date).format('YYYY/MM/DD HH:mm');
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