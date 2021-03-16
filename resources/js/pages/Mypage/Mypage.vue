<!--=========================-->
<!--マイページ・アカウント設定画面-->
<!--=========================-->
<template>
  <div class="l-container__content">
    <!-- 読み込み中の時 -->
    <div v-if="isLoading">
      <Loading />
    </div>

    <!-- ユーザーが存在しない時 -->
    <div v-else-if="isNothingUser" class="p-container">
      <NothingUser />
    </div>

    <!-- メインレイアウト -->
    <div v-else class="p-container">
      <div class="p-mypage">
        <!-- プロフィール -->
        <div class="p-mypage__column">
          <h2>{{ this.userName }}さんのマイページ</h2>
          <pre>プロフィール400文字まで</pre>
          <span>ホームページ</span>
          <span>所属・組織</span>
          <span>住んでいるところ</span>
        </div>

        <!-- 投稿記事一覧 -->
        <!-- TODO 投稿した記事・コメントした記事をタブで切り替えられるようにする -->
        <div class="p-mypage__column">

        </div>
      </div>

    </div>

  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import NothingUser from './NothingUser.vue';
import {NOT_FOUND, OK} from "../../util";

export default {
  props: {
    username: {
      type: String,
      required: true,
    }
  },
  data() {
    return {
      user: {},
      loading: false,
      nothingUser: false,
    };
  },
  computed: {
    userName() {
      return this.username;
    },
    isLoading() {
      return this.loading;
    },
    isNothingUser() {
      return this.nothingUser;
    },
  },
  methods: {
    // 指定したユーザーの情報を取得する
    async fetchUser() {
      const response = await axios.get(`/user/info/${this.userName}`)

      // ユーザーが存在しなかった時の処理
      if (response.status === NOT_FOUND) {
        this.nothingUser = true
        return false
      }
      // その他エラー時の処理
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false
      }
      console.log(response.data)
      this.user = response.data
    }
  },
  components: {
    Loading,
    NothingUser,
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchUser()
      },
      immediate: true
    }
  }
};
</script>