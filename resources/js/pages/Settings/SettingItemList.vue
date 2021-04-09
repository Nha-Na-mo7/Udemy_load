<!-- 設定項目リスト -->
<template>
  <div class="p-setting__list">
    <ul class="p-setting__list--container">
      <li class="">
        <RouterLink
            class="p-setting__list--item"
            to="/settings/account"
        >アカウント</RouterLink>
      </li>
      <li class="">
        <RouterLink
            class="p-setting__list--item"
            to="/settings/password">
          パスワード</RouterLink>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "SettingItemList"
}
</script>

<style scoped></style>