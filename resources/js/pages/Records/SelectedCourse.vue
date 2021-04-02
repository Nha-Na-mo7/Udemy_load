<!-- ========================== -->
<!-- 選択されたコースのコンポーネント -->
<!-- ========================== -->
<template>
  <div class="p-course p-course__selected">

    <!-- 講座名とリンク -->
    <div class="p-course__card--title">
      <h2>
        <a
            class="p-course__card--title--link"
            :href="getUrl | addUdemyBaseUrl"
            target="_blank"
            rel="noopener noreferrer"
        >{{ getTitle }}</a
        >
      </h2>
      <!-- 講師名 -->
      <p class="p-course__card--instructor">{{ getInstructor }}</p>
    </div>

    <!-- サムネイル -->
    <img :src="getImage" alt="">

    <div>
      <label for=""></label>
      <textarea
          name=""
          id=""
          :value="this.courseDescription"
          @input="$emit('input', $event.target.value)"
          class="c-form__textarea p-course__selected--textarea"
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
import Loading from "../../components/Loading";

export default {
  props: {
    course: {
      type: Object,
      required: true,
    },
    createflg: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      courseObj: this.course,
      existCourse: false,
      courseDescription: this.course.description
    }
  },
  computed: {
    // コースオブジェクト
    getCourseObj() {
      return this.checkCreateOrEdit(this.course.courseObject, this.course)
    },
    // コース名
    getTitle() {
      return this.getCourseObj.title;
    },
    // コースのURL(ベースのURLを前に付与しなければならない)
    getUrl() {
      return this.getCourseObj.url;
    },
    // 講師名
    getInstructor() {
      // visible_instructorsがプロパティとして存在するか確認
      let createInstructorName = ''
      if ('visible_instructors' in this.getCourseObj) {
        createInstructorName = this.getCourseObj.visible_instructors[0].title
      }
      return this.checkCreateOrEdit(
          createInstructorName,
          this.getCourseObj.instructor
      )
    },
    // 画像
    getImage() {
      return this.checkCreateOrEdit(
          this.getCourseObj.image_240x135,
          this.getCourseObj.image_url
      )
    },
    // オブジェクトそのもの
    getCourseObject() {
      return this.getCourseObj
    },
  },
  methods: {
    // 毎度if文を書かないようにするための関数
    checkCreateOrEdit(createReturnVal, editReturnVal) {
      if (this.createflg) {
        return createReturnVal
      }else{
        return editReturnVal
      }
    },
    // コースの削除
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
  filters: {
    addUdemyBaseUrl: (url) => UDEMY_BASE_URL + url
  }
}
</script>

<style scoped>

</style>