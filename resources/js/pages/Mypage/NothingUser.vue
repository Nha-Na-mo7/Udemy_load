<!--==================================-->
<!-- 存在しない or 削除されたユーザーの場合 -->
<!--==================================-->
<template>
  <div>
    <h2 class="f_nothing--title">ユーザーが見つかりませんでした。</h2>
    <p>お探しのユーザーは存在しない、あるいは削除された可能性があります。</p>
  </div>
</template>

<script>
export default {
name: "NothingUser"
}
</script>

<style scoped>
.f-nothing--title {
  font-size: 40px;
}
</style>