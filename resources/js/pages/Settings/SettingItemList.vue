<!-- 設定項目リスト -->
<template>
  <div>
    <RouterLink class="c-btn" to="/settings/profile">プロフィール</RouterLink>
    <RouterLink class="c-btn" to="/settings/account">アカウント</RouterLink>
    <RouterLink class="c-btn" to="/settings/password">パスワード</RouterLink>
  </div>
</template>

<script>
export default {
  name: "SettingItemList"
}
</script>

<style scoped></style>