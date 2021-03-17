<!-- ========================== -->
<!-- 選択されたコースのコンポーネント -->
<!-- ========================== -->
<template>
  <div class="p-course p-course__selected">
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

    <!-- サムネイル -->
    <img :src="getImage" alt="">

    <div>
      <label for=""></label>
      <textarea
          name=""
          id=""
          :value="courseDescription"
          @input="$emit('input', $event.target.value)"
          class="c-form__textarea f-textarea"
          placeholder="説明(このコースではどんなことが学べますか？また、後に学ぶコースのためにどういった点が必要になりますか？)"
          maxlength="200"
      ></textarea>
    </div>

    <!-- 削除ボタン -->
    <div>
      <button
          class="c-btn"
          @click="deleteCourse"
      >削除する</button>
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
  data() {
    return {
      courseDescription: this.course.description
    }
  },
  computed: {
    // コースオブジェクト
    getCourseObj() {
      return this.course.courseObject
    },
    // コース名
    getTitle() {
      return this.getCourseObj.title;
    },
    // コースのURL(ベースのURLを前に付与しなければならない)
    getUrl() {
      return UDEMY_BASE_URL + this.getCourseObj.url;
    },
    // 講師名
    getInstructor() {
      return this.getCourseObj.visible_instructors[0].title;
    },
    // 画像
    getImage() {
      return this.getCourseObj.image_240x135;
    },
    // オブジェクトそのもの
    getCourseObject() {
      return this.getCourseObj
    },
  },
  methods: {
    deleteCourse() {
      if (
          confirm('選択したコースを削除します。')
      )
      this.$emit('deleteCourse')
    }
  }
}
</script>

<style scoped>
.f-textarea {
  width: 100%;
}
</style>