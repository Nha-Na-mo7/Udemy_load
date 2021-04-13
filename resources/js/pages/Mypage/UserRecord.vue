
<!-- マイページの投稿履歴画面で表示させるコンポーネント-->

<template>
  <article class="p-mypage__record-list-item">
    <div class="p-mypage__record-list-item--column p-mypage__record-list-item--column--left">
      <h2 class="p-mypage__record-list-item--title">
        <RouterLink
            class=""
            :to="`/records/${recordId}`"
            :title="`${this.title}`"
        >
          {{ this.title }}
        </RouterLink>
      </h2>
      <p v-html="description" class="p-mypage__record-list-item--description"></p>

    </div>
    <div class="p-mypage__record-list-item--column p-mypage__record-list-item--column--right">
      <!-- 編集ボタン/投稿者自身の場合のみ-->
      <RouterLink
          v-if="isOwner"
          class="u-mb-l"
          :to="`/records/${recordId}/edit`">
        <i class="fas fa-pencil-alt p-mypage__record-list--icon"></i>
      </RouterLink>
    </div>



  </article>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  computed: {
    recordId() {
      return this.item.id
    },
    title() {
      return this.item.title
    },
    description() {
      return this.item.description
    },
    // 投稿者と自分が同一か
    isOwner() {
      return this.item.owner.name === this.$store.getters['auth/username']
    }
  },
  data() {
    return {
      currentPath: this.$route.path
    }
  },
}
</script>

<style scoped>

</style>