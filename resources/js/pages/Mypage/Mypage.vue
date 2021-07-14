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
          <h2 class="p-mypage__title u-mb-xl">
            {{ this.userName }}さんのマイページ
          </h2>

          <div class="p-mypage__profiles">
            <!-- 自己紹介 -->
            <div class="p-mypage__profiles--proftext">
              <h3 class="u-mb-s"><i class="fas fa-comment c-icon__fa--default"></i> 自己紹介</h3>
              <p>{{ this.userProfileText }}</p>
            </div>
            <!-- 所属企業・組織 -->
            <div class="p-mypage__profiles--organization">
              <p><i class="fas fa-building c-icon__fa--default"></i> {{ this.userOrganization }}</p>
            </div>
          </div>

          <!-- ユーザー設定 -->
          <div class="u-text--center" v-if="isAuthUser">
            <RouterLink class="c-btn" to="/settings/profile">
              <i class="fas fa-cog"></i> ユーザー設定
            </RouterLink>
          </div>
        </div>

        <!-- 投稿記事一覧 -->
        <div class="p-mypage__column">
          <h2 class="p-mypage__title u-mb-xl">投稿履歴</h2>
          <UserRecordList v-if="isExistUserObj" :user="this.user" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import NothingUser from './NothingUser.vue';
import UserRecordList from './UserRecordList.vue';
import { NOT_FOUND, OK } from '../../util';

export default {
  props: {
    username: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      user: {},
      loading: true,
      nothingUser: false,
    };
  },
  computed: {
    isExistUserObj() {
      return !!Object.keys(this.user).length;
    },
    userName() {
      return this.username;
    },
    userOrganization() {
      return this.user.organization ?? '';
    },
    userProfileText() {
      return this.user.profile_text ?? '- プロフィールは設定されていません -';
    },
    isLoading() {
      return this.loading;
    },
    isNothingUser() {
      return this.nothingUser;
    },
    // マイページが認証中のユーザー(=自分)と同一か
    isAuthUser() {
      return this.userName === this.$store.getters['auth/username'];
    },
  },
  methods: {
    async fetchUser() {
      if(this.isAuthUser) {
        // 自分のマイページの場合はstoreから情報を引き出し、余計な通信を行わない
        this.user = this.$store.getters['auth/user']
        this.loading = false;
      }else{
        // 自分以外のマイページの時は指定したユーザーの情報を取得
        const response = await axios.get(`/user/info/${this.userName}`);

        switch (response.status) {
          case NOT_FOUND:
            this.nothingUser = true;
            this.loading = false;
            return false;
          case OK:
            this.user = response.data;
            this.loading = false;
            break;
            // NOT_FOUND以外のエラーの場合
          default:
            this.$store.commit('error/setCode', response.status);
            return false;
        }
      }
    },
  },
  components: {
    Loading,
    NothingUser,
    UserRecordList,
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchUser();
        const titleUserName = this.nothingUser ? 'ユーザーが見つかりません' : `${this.userName}さんのマイページ`
        document.title = `${titleUserName} |  UdemyLoad`
      },
      immediate: true,
    },
  },
};
</script>
