<!--コース検索結果で表示させるコンポーネント-->
<template>
  <div class="p-course__card">

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

      <!-- 追加するボタン -->
      <button class="c-btn" @click="addCourse">追加する</button>
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
  data() {
    return {
      courseData: {
        courseObject: this.course,
        description: ''
      }
    }
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
  methods: {
    addCourse() {
      // 親コンポーネントの配列にコースオブジェクトを格納する
      this.$emit("addCourse", this.courseData);
    }
  }
};
</script>

<style scoped>
@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>
