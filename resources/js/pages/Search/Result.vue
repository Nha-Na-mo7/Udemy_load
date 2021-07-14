<!--検索結果画面で表示させる結果1つ1つ-->
<template>
  <article class="p-search__result">
    <div class="p-search__result__main">
      <RouterLink
          class="p-search__result--username"
          :to="`/mypage/${this.ownerName}`"
      >@{{ this.ownerName }}
      </RouterLink>
      <br class="u-sp__block" />
      <span class="p-search__result--date"
      >{{ item.created_at | recordAt }}投稿</span
      >
    </div>
    <h2 class="p-search__result--title">
      <RouterLink
          class=""
          :to="`/records/${recordId}`"
          :title="`${this.title}`"
      >
        {{ this.title }}
      </RouterLink>
    </h2>
    <p class="p-search__result--description">{{ this.description }}</p>
  </article>

</template>

<script>
import moment from 'moment';

export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  computed: {
    recordId() {
      return this.item.id;
    },
    title() {
      return this.item.title;
    },
    description() {
      return this.item.description;
    },
    ownerName() {
      return this.item.owner.name;
    },
  },
  data() {
    return {
      currentPath: this.$route.path,
    };
  },
  filters: {
    recordAt(date) {
      return moment(date).format('YYYY年M月D日HH時mm分');
    },
  },
};
</script>

<style scoped>

</style>