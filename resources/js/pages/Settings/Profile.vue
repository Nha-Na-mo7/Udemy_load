<template>
  <div class="l-container__setting u-flex u-space-between">
    <SettingItemList />

    <h1>公開用プロフィールの設定</h1>
    <div>
      <p>名前</p>
      <p>ホームページ/サイト/ブログなど</p>
      <p>所属企業・組織など</p>
      <p>住んでいる場所</p>
      <p>自己紹介(160字以内)</p>
    </div>

    <div>
      <button class="c-btn">更新する</button>
    </div>
  </div>
</template>

<script>

import SettingItemList from "./SettingItemList.vue";

export default {
  components: {
    SettingItemList
  }
}
</script>

<style scoped>

</style>