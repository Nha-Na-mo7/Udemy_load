<!-- ========================== -->
<!-- 選択されたコースのコンポーネント -->
<!-- ========================== -->
<template>
  <div class="p-course p-course__selected">
    <!-- span -->
    <span v-if="getIndex" class="p-course--index-bar"></span>

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
    <img :src="getImage" alt="" class="p-course__card--img" />

    <div class="u-mt-l">
      <label class="c-form__label" for="course_description">コースの説明</label>
      <textarea
        name=""
        id="course_description"
        :value="this.courseDescription"
        @input="$emit('input', $event.target.value)"
        class="c-form__textarea p-course__selected--textarea"
        placeholder="このコースではどんなことが学べますか？また、後に学ぶコースのためにどういった点が必要になりますか？"
        maxlength="250"
      ></textarea>
    </div>

    <!-- 削除ボタン -->
    <button class="c-btn c-btn__course--delete" @click="deleteCourse">
      <i class="fas fa-times p^mypage__record-list--icon"></i>
    </button>
  </div>
</template>

<script>
import { UDEMY_BASE_URL } from '../../util';
import Loading from '../../components/Loading';

export default {
  props: {
    course: {
      type: Object,
      required: true,
    },
    index: {
      type: Number,
      required: true,
    },
    createflg: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      courseObj: this.course,
      existCourse: false,
      courseDescription: this.course.description,
    };
  },
  computed: {
    // コースオブジェクト
    getCourseObj() {
      return this.checkCreateOrEdit(this.course.courseObject, this.course);
    },
    // コース名
    getTitle() {
      return this.getCourseObj.title;
    },
    // コースのURL(ベースのURLを前に付与しなければならない)
    getUrl() {
      return this.getCourseObj.url;
    },
    // コースのインデックス(何番目か)
    getIndex() {
      return this.index
    },
    // 講師名
    getInstructor() {
      // visible_instructorsがプロパティとして存在するか確認
      let createInstructorName = '';
      if ('visible_instructors' in this.getCourseObj) {
        createInstructorName = this.getCourseObj.visible_instructors[0].title;
      }
      return this.checkCreateOrEdit(
        createInstructorName,
        this.getCourseObj.instructor,
      );
    },
    // 画像
    getImage() {
      return this.checkCreateOrEdit(
        this.getCourseObj.image_240x135,
        this.getCourseObj.image_url,
      );
    },
    // オブジェクトそのもの
    getCourseObject() {
      return this.getCourseObj;
    },
  },
  methods: {
    // 毎度if文を書かないようにするための関数
    checkCreateOrEdit(createReturnVal, editReturnVal) {
      if (this.createflg) {
        return createReturnVal;
      } else {
        return editReturnVal;
      }
    },
    // コースの削除
    deleteCourse() {
      if (confirm('選択したコースを削除します。')) this.$emit('deleteCourse');
    },
  },
  components: {
    Loading,
  },
  filters: {
    addUdemyBaseUrl: (url) => UDEMY_BASE_URL + url,
  },
};
</script>

<style scoped></style>
