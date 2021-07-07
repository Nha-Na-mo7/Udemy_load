<template>
  <div class="p-search">
    <div class="p-search__container">
      <form action="" class="p-search__form c-form">
        <input type="text" name="query" class="c-input">
        <button class="p-search__btn c-btn">
          <i class="fas fa-search c-icon__fa--default"></i> 検索
        </button>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  watch: {
    $route: {
      async handler() {
        const num = Math.random() * 10
        console.log(num)
        const title = num > 5 ? '検索' : '何も入っていません'
        document.title = `${title} |  UdemyLoad`
      },
      immediate: true,
    },
  },
}
</script>
<style scoped>
</style>