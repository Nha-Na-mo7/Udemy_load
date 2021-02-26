<!--コース検索結果で表示させるコンポーネント-->
<template>
  <div class="p-course__item">

    <!-- 記事のタイトル -->
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
      <p>{{ getInstructor }}</p>
      <img :src="getImage" alt="">
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
    }
  },
};
</script>

<style scoped>
.p-course__item {
  animation: fadeIn 1s;
}
@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>
