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
          <h2 class="p-mypage__title u-mb-xl">{{ this.userName }}さんのマイページ</h2>

          <!-- プロフィール編集 -->
          <div class="u-text--center" v-if="isAuthUser">
            <button class="c-btn">
              <RouterLink to="/settings/account">
                <i class="fas fa-cog"></i> アカウント設定
              </RouterLink>
            </button>
          </div>
        </div>

        <!-- 投稿記事一覧 -->
        <!-- TODO 投稿した記事・コメントした記事をタブで切り替えられるようにする -->
        <div class="p-mypage__column">
          <h2 class="p-mypage__title u-mb-xl">投稿履歴</h2>
          <UserRecordList
              v-if="isExistUserObj"
              :user="this.user"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import NothingUser from './NothingUser.vue';
import UserRecordList from './UserRecordList.vue';
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
    isExistUserObj() {
      return !!Object.keys(this.user).length
    },
    userName() {
      return this.username;
    },
    isLoading() {
      return this.loading;
    },
    isNothingUser() {
      return this.nothingUser;
    },
    // マイページが認証中のユーザー(=自分)と同一か
    isAuthUser() {
      return this.userName === this.$store.getters['auth/username']
    }
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
      this.user = response.data
    }
  },
  components: {
    Loading,
    NothingUser,
    UserRecordList
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