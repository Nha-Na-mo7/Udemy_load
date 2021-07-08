<template>
  <div class="p-record__list">
    <!-- 検索(コンポーネント化) -->
    <div class="p-record__list--search">
      <form action="" class="p-search__form c-form">
        <input
            type="text"
            class="c-form__input c-form__input--recordSearch"
        >
        <button
            class="p-search__btn c-btn"
            @click=""
        >
          <i class="fas fa-search c-icon__fa--default"></i> 検索
        </button>
      </form>
    </div>

    <!-- みんなのロードマップ -->
    <div class="p-record__list--inner" id="records">
      <h2 class="p-record__list--title">みんなのロードマップ</h2>
      <Record
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
            {{ this.getStartCount }} ~ {{ this.getEndCount }} / 全
            {{ this.records.length }} 記事
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { OK } from '../../util.js';
import Record from './Record';

import Vue from 'vue';
import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);

export default {
  props: {
    p: {
      type: Number,
      required: false,
      default: 1,
    },
  },
  data() {
    return {
      records: [],
      parPage: 10,
      currentPage: 1,
      lastPage: 0,
    };
  },
  computed: {
    // ======================
    // ページネーション用
    // ======================
    // ページネーション用にアカウントリストを細分化する
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
    // ページトップの座標までスクロールするためのプロパティ
    getRecordsRect() {
      var $e = $('#records');
      return $e.offset().top;
    },
  },
  methods: {
    // ロードマップ一覧の取得
    async fetchCourse() {
      const response = await axios.get(`/records/index`);

      // エラー時
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.records = response.data;
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
    Record,
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
