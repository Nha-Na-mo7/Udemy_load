
<!-- レコード一覧画面で表示させるコンポーネント-->

<template>
  <div class="records">
    <div class="records__main">
    <!--  <span class="records__date">{{ item.created_at }}</span> -->
      <span class="records__username">ユーザー名: {{ this.ownerName }}</span>
    </div>
    <h2 class="records__title">
      <RouterLink
        class=""
        :to="`/records/${recordId}`"
        :title="`${this.title}`"
      >
      タイトル: {{ this.title }}
      </RouterLink>
    </h2>
    <p class="records__description">説明: {{ this.description }}</p>
  </div>
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
.records {
  border: 1px solid #000;
  background: #d8f1ff;
  margin-bottom: 15px;
}
.records__title {
  font-size: 20px;
}
.records__description {
  font-size: 12px;
  line-height: 1.5;
}
</style>