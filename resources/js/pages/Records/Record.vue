
<!-- レコード一覧画面で表示させるコンポーネント-->

<template>
  <article class="p-record__list-item">
    <div class="p-record__list-item__main">
      <RouterLink
          class="p-record__list-item--username"
          :to="`/mypage/${ this.ownerName }`"
      >@{{ this.ownerName }}
      </RouterLink>
      <br class="u-sp__block">
      <span class="p-record__list-item--date">{{ item.created_at | recordAt }}投稿</span>
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
import moment from 'moment';

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
  filters: {
    recordAt(date) {
      return moment(date).format('YYYY年M月D日HH時mm分');
    },
  }
}
</script>

<style scoped>

</style>