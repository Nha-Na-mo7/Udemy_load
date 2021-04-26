<!-- マイページの投稿履歴画面で表示させるコンポーネント-->

<template>
  <article class="p-mypage__record-list-item">
    <div
      class="p-mypage__record-list-item--column p-mypage__record-list-item--column--left"
    >
      <!-- 更新時刻 -->
      <p class="p-mypage__record-list-item--date">
        投稿時刻 {{ this.createdAt | recordAt }}
      </p>
      <p class="p-mypage__record-list-item--date" v-show="checkUpdated">
        最終更新 {{ this.updatedAt | recordAt }}
      </p>

      <h2 class="p-mypage__record-list-item--title">
        <RouterLink
          class=""
          :to="`/records/${recordId}`"
          :title="`${this.title}`"
        >
          {{ this.title }}
        </RouterLink>
      </h2>
      <p class="p-mypage__record-list-item--description">
        {{ this.description }}
      </p>
    </div>
    <div
      class="p-mypage__record-list-item--column p-mypage__record-list-item--column--right"
    >
      <!-- 編集ボタン/投稿者自身の場合のみ-->
      <RouterLink
        v-if="isOwner"
        class="u-mb-l"
        :to="`/records/${recordId}/edit`"
      >
        <i class="fas fa-pencil-alt c-icon__fa u-text--right"></i>
      </RouterLink>
    </div>
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
    // 投稿時刻
    createdAt() {
      return this.item.created_at;
    },
    // 更新時刻
    updatedAt() {
      return this.item.updated_at;
    },
    // 更新されているか(投稿時刻と更新時刻が同一ではないか)
    checkUpdated() {
      return this.createdAt < this.updatedAt;
    },
    // 投稿者と自分が同一か
    isOwner() {
      return this.item.owner.name === this.$store.getters['auth/username'];
    },
  },
  data() {
    return {
      currentPath: this.$route.path,
    };
  },
  filters: {
    recordAt(date) {
      return moment(date).format('YYYY/MM/DD HH:mm');
    },
  },
};
</script>

<style scoped></style>
