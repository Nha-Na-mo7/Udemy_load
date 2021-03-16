
<!-- レコード一覧画面で表示させるコンポーネント-->

<template>
  <article class="p-record__list-item">
    <div class="p-record__list-item__main">
    <!--  <span class="p-record__list-item__date">{{ item.created_at }}</span> -->
      <RouterLink
          class="p-record__list-item__username"
          :to="`/mypage/${ this.ownerName }`"
      >ユーザー名: {{ this.ownerName }}
      </RouterLink>
    </div>
    <h2 class="p-record__list-item--title">
      <RouterLink
        class=""
        :to="`/records/${recordId}`"
        :title="`${this.title}`"
      >
      {{ this.title }}
      </RouterLink>
    </h2>
    <p v-html="description" class="p-record__list-item--description"></p>
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
    ownerName() {
      return this.item.owner.name
    },
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