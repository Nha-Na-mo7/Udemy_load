<!--==================================-->
<!-- 存在しない or 削除されたユーザーの場合 -->
<!--==================================-->
<template>
  <div>
    <h2 class="f-nothing__title">ユーザーが見つかりませんでした。</h2>
    <p>お探しのユーザーは存在しない、あるいは削除された可能性があります。</p>
  </div>
</template>

<script>
export default {
name: "NothingUser"
}
</script>

<style scoped>
.f-nothing__title {
  font-size: 60px;
  font-weight: bold;
}
</style>