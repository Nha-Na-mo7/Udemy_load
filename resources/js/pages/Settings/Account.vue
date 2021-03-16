<template>
  <div>
    <SettingItemList />

    <h1>アカウントに関する設定</h1>
    <p>ユーザー名の更新</p>

    <!-- アカウントの削除 -->
    <div class="">
      <div class="">
        <h2 class="">
          <i class="fas fa-sign-out-alt"></i>
          アカウントを削除する
        </h2>
      </div>
      <div class="">
        <div class="">
          <p>
            アカウントを削除すると元に戻すことはできなくなります。
          </p>
        </div>
        <div class="">
          <button class="c-btn" @click="withdraw">退会する</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import SettingItemList from "./SettingItemList.vue";

export default {
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
    SettingItemList
  }
}
</script>

<style scoped>

</style>