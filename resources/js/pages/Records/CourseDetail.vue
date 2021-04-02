
<!-- コース詳細画面でのコースコンポーネント -->

<template>
  <div class="p-course__card">
    <!-- indexバー -->
    <span class="p-course__card--line" :detail-index="this.recordIndex"></span>
    <!-- サムネイル -->
    <img :src="getImage" alt="">
    <!-- 講座名とリンク -->
    <div class="p-course__card--title">
      <h2>
        <a
            class="p-course__card--title--link"
            :href="getUrl"
            target="_blank"
            rel="noopener noreferrer"
        >{{ getTitle }}</a
        >
      </h2>
      <!-- 講師名 -->
      <p class="p-course__card--instructor">{{ getInstructor }}</p>
    </div>
    <div>
      <p class="p-course__card--description">{{ getDescription }}</p>
    </div>
  </div>
</template>

<script>
import {UDEMY_BASE_URL} from '../../util.js'

export default {
  props: {
    course: {
      type: Object,
      required: true,
    },
    index: {
      type: Number,
      required: true,
    }
  },
  computed: {
    // 描画されているのがn番目か
    recordIndex() {
      return this.index + 1
    },
    // コース名
    getTitle() {
      return this.course.title;
    },
    // コースのURL(ベースのURLを前に付与しなければならない)
    getUrl() {
      return UDEMY_BASE_URL + this.course.url;
    },
    // 講師名
    getInstructor() {
      return this.course.instructor;
    },
    // 画像
    getImage() {
      return this.course.image_url;
    },
    // 説明
    getDescription() {
      return this.course.description;
    },
    // オブジェクトそのもの
    getCourseObject() {
      return this.course
    }
  },
};
</script>

<style scoped>

</style>