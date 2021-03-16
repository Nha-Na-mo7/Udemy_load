<!--=========================-->
<!--マイページ・アカウント設定画面-->
<!--=========================-->
<template>
  <div class="l-container__content">

    <h2>マイページ {{ id }}</h2>

    <!-- 読み込み中の時 -->
    <div v-if="isLoading">
      <Loading />
    </div>

    <!-- メインレイアウト -->
    <div v-else class="p-container dummyflex">
      <div class="p-mypage">
        <div class="p-mypage__column">
          <!-- メールアドレス -->
          <div class="p-documentbox c-documentbox">
            <div class="c-documentbox__header">
              <h2 class="c-documentbox__title">
                <i class="far fa-envelope"></i>
                メールアドレス
              </h2>
              <RouterLink to="/mypage/profile">設定する ></RouterLink>
            </div>
            <div class="c-documentbox__body">
              <h2 class="c-documentbox__item c-documentbox__item--info">
                登録メールアドレス
              </h2>
              <p class="c-documentbox__item">{{ this.authMail }}</p>
            </div>
          </div>

          <!-- パスワード -->
          <div class="p-documentbox c-documentbox">
            <div class="c-documentbox__header">
              <h2 class="c-documentbox__title">
                <i class="fas fa-key"></i>
                パスワード</h2>
              <RouterLink to="/mypage/password">設定する ></RouterLink>
            </div>
            <div class="c-documentbox__body" v-if="isExistPassword">
              <!-- 実際の桁数に関係なく********とする -->
              <h2 class="c-documentbox__item c-documentbox__item--info">
                パスワード設定済
              </h2>
              <p class="c-documentbox__item">＊＊＊＊＊＊＊＊</p>
            </div>
            <div class="c-documentbox__body" v-else>
              <h2 class="c-documentbox__item c-documentbox__item--info">
                パスワードは設定されていません
              </h2>
            </div>
          </div>
        </div>

        <div class="p-mypage__column">
          <!-- 退会処理 -->
          <div class="p-documentbox c-documentbox">
            <div class="c-documentbox__header">
              <h2 class="c-documentbox__title">
                <i class="fas fa-sign-out-alt"></i>
                退会する
              </h2>
            </div>
            <div class="c-documentbox__body">
              <div class="c-documentbox__item">
                <p>
                  退会するとサービスがご利用いただけなくなります。
                </p>
              </div>
              <div class="c-documentbox__footer">
                <button class="c-btn" @click="withdraw">退会する</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
const PAGE_TITLE = 'マイページ';

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
      password: false,
      mail: '',
      test_user_flg: false
    };
  },
  computed: {
    pageTitle() {
      return PAGE_TITLE;
    },
    isLoading() {
      return this.loading;
    },
    isExistPassword() {
      return this.password;
    },
    isTestUserFlg() {
      return this.test_user_flg;
    },
    authMail() {
      return this.mail;
    },
  },
  methods: {
    // 退会処理 PHP側でデータ削除して、フロント側で画面遷移させる。
    async withdraw() {
      if (
          confirm(
              '【 退会しますか？ 】\n退会すると各種サービスのご利用ができなくなります。',
          )
      )
      {
        console.log('ここで退会処理が行われます')
      }
    },
  },
  components: {
    Loading,
  },
};
</script>