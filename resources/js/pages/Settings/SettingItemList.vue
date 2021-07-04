<!-- 設定項目リスト -->
<template>
  <div class="p-setting__list">
    <ul class="p-setting__list--container">
      <li class="">
        <RouterLink class="p-setting__list--item" to="/settings/profile">
          <i class="fas fa-key"></i>
          プロフィール
        </RouterLink>
      </li>
      <li class="">
        <RouterLink class="p-setting__list--item" to="/settings/account">
          <i class="fas fa-cog"></i>
          アカウント
        </RouterLink>
      </li>
      <li class="">
        <RouterLink class="p-setting__list--item" to="/settings/password">
          <i class="fas fa-key"></i>
          パスワード
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'SettingItemList',
};
</script>

<style scoped></style>
