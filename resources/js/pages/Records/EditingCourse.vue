<template>
  <div>
    <div v-if="!existCourse">
      <Loading></Loading>
    </div>

    <div v-else class="p-course__selected">
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
      <img :src="this.course.image_url" alt="">

      <div>
        <label for="course_description"></label>
        <textarea
            name=""
            id="course_description"
            :value="courseDescription"
            @input="$emit('input', $event.target.value)"
            class="p-record__edit--textarea c-form__textarea"
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
  </div>
</template>

<script>
import {UDEMY_BASE_URL} from "../../util";
import Loading from "../../components/Loading"

export default {
  props: {
    course: {
      type: Object,
      required: true,
    },
  },
  data() {
    console.log(this.course)
    return {
      existCourse: false,
      courseDescription: this.course.description
    }
  },
  computed: {
    // コースオブジェクト
    getCourseObj() {
      return this.course
    },
    // コース名
    getTitle() {
      return this.getCourseObj.title;
    },
    // コースのURL(ベースのURLを前に付与しなければならない)
    getUrl() {
      return UDEMY_BASE_URL + this.getCourseObj.url;
    },
    // 画像
    getImage() {
      return this.getCourseObj.image_url;
    },
    // 講師名
    getInstructor() {
      return this.getCourseObj.instructor;
    },
  },
  methods: {
    deleteCourse() {
      if (
          confirm('選択したコースを削除します。')
      )
        this.$emit('deleteCourse')
    }
  },
  components: {
    Loading,
  },
  mounted() {
    this.existCourse = true;
  }
}
</script>

<style scoped>

</style>