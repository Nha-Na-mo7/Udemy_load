<!-- マイページで表示させる、投稿済みのロードマップ一覧用コンポーネント -->
<template>
  <div class="p-mypage__record-list">
    <div class="p-mypage__record-list--inner">
      <div v-if="isLoading">
        <Loading />
      </div>
      <div v-else-if="!!recordLength">
        <UserRecord
          v-for="Record in getRecordsItems"
          :key="Record.id"
          :item="Record"
        />
        <!-- ページネーション -->
        <div class="u-text--center">
          <paginate
            v-model="currentPage"
            :page-count="getPageCount"
            :page-range="3"
            :margin-pages="1"
            :click-handler="clickCallback"
            :prev-text="'<'"
            :next-text="'>'"
            :break-view-class="'c-paginate__item--break-view'"
            :hide-prev-next="true"
            :containerClass="'c-paginate'"
            :page-class="'c-paginate__item'"
            :page-link-class="'c-paginate__link'"
            :prev-class="'c-paginate__item c-paginate__item--prev'"
            :prev-link-class="'c-paginate__link'"
            :next-class="'c-paginate__item c-paginate__item--next'"
            :next-link-class="'c-paginate__link'"
            :active-class="'c-paginate__item--active'"
            list=""
            name=""
          >
          </paginate>
          <div>
            <p>
              {{ this.getStartCount }} - {{ this.getEndCount }} / 全
              {{ this.recordLength }} 投稿
            </p>
          </div>
        </div>
      </div>
      <div v-else>
        <NothingUserRecord />
      </div>
    </div>
  </div>
</template>

<script>
import { OK } from '../../util.js';
import Loading from '../../components/Loading.vue';
import UserRecord from './UserRecord';
import NothingUserRecord from './NothingUserRecord';

import Vue from 'vue';
import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);

export default {
  props: {
    user: {
      type: Object,
      required: true,
    },
    p: {
      type: Number,
      required: false,
      default: 1,
    },
  },
  data() {
    return {
      loading: true,
      records: [],
      parPage: 10,
      currentPage: 1,
    };
  },
  computed: {
    isLoading() {
      return this.loading;
    },
    userName() {
      return this.user.name;
    },
    userId() {
      return this.user.id;
    },
    recordLength() {
      return this.records.length;
    },
    // ======================
    // ページネーション用
    // ======================
    // ページネーション用に細分化
    getRecordsItems: function () {
      let current = this.currentPage * this.parPage;
      let start = current - this.parPage;
      return this.records.slice(start, current);
    },
    // 総ページ数
    getPageCount: function () {
      return Math.ceil(this.records.length / this.parPage);
    },
    // 現在の表示開始箇所 (21-30件表示中 の21の部分)
    getStartCount: function () {
      return (this.currentPage - 1) * this.parPage + 1;
    },
    // 現在の表示終了箇所 (21-30件表示中 の30の部分)
    getEndCount: function () {
      let current = this.currentPage * this.parPage;
      let over_check = current > this.records.length;
      if (over_check) {
        return this.records.length;
      } else {
        return current;
      }
    },
    // リストの座標までスクロールするためのプロパティ
    getRecordsRect() {
      // var $e = $('#records');
      return 0;
    },
  },
  methods: {
    // マイページユーザーが投稿したロードマップ一覧の取得
    async fetchCourse() {
      const response = await axios.get(`/records/index/${this.userId}`);

      // エラー時
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }
      this.records = response.data;
      this.loading = false;
    },
    // ======================
    // ページネーション用
    // ======================
    clickCallback: function (pageNum) {
      this.currentPage = Number(pageNum);
    },

    scrollTop: function () {
      window.scrollTo({
        top: this.getRecordsRect,
        behavior: 'smooth',
      });
    },
  },
  components: {
    Loading,
    UserRecord,
    NothingUserRecord,
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchCourse();
      },
      immediate: true,
    },
  },
};
</script>

<style scoped></style>
