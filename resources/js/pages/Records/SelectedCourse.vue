<!-- ================= -->
<!-- 選択済みのコース一覧 -->
<!-- ================= -->
<template>
  <div>
    <h1>選択コース</h1>
    <!-- サムネイル -->
    <img :src="getImage" alt="">
    <!-- 講座名とリンク -->
    <div class="p-course__item--title">
      <h2>
        <a
            class="p-course__item--title--link"
            :href="getUrl"
            target="_blank"
            rel="noopener noreferrer"
        >{{ getTitle }}</a
        >
      </h2>
      <!-- 講師名 -->
      <p class="p-course__item--instructor">{{ getInstructor }}</p>
    </div>
  </div>
</template>

<script>
import {UDEMY_BASE_URL} from "../../util";

export default {
  props: {
    course: {
      type: Object,
      required: true,
    },
  },
  computed: {
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
      return this.course.visible_instructors[0].title;
    },
    // 画像
    getImage() {
      return this.course.image_240x135;
    },
    // オブジェクトそのもの
    getCourseObject() {
      return this.course
    }
  },
}
</script>

<style scoped>

</style>