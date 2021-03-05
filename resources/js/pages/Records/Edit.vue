<template>
  <div class="f_page">
    <h2>講座登録</h2>

    <!-- 現在追加されているコース-->
    <div>
      <SelectedCourse
        v-for="(Course, index) in selectedCourses"
        :key="Course.id"
        :course="Course"
        @deleteCourse="deleteCourseObject(index)"
      />
    </div>

    <!-- コースを追加する -->
    <div>
      <button class="c-btn" @click="toggleModalFlg">
        コースを追加する
      </button>
    </div>

    <!-- モーダルがONになったら表示される -->
    <!-- TODO コンポーネントわけすること -->
    <div v-if="modalFlg">
      <SearchModal
          @pushCourseObjToSelectedCoursesArr="pushCourseObjToSelectedCoursesArr"
          @toggleModal="toggleModalFlg"
      />
    </div>

    <!-- 投稿する -->
    <div>
      <button class="c-btn">投稿する</button>
    </div>
  </div>
</template>

<script>
import Loading from '../../components/Loading.vue';
import SelectedCourse from './SelectedCourse.vue';
import SearchModal from './SearchModal.vue';

export default {
  data() {
    return {
      modalFlg: false,
      selectedCourses: [],
    }
  },
  methods: {
    // モーダルフラグを切り替え
    toggleModalFlg() {
      this.modalFlg = !this.modalFlg;
    },
    // 選択済みコースに追加する
    pushCourseObjToSelectedCoursesArr(e) {
      this.selectedCourses.push(e);
    },
    // オブジェクトを削除する
    deleteCourseObject(index) {
      this.selectedCourses.splice(index, 1)
    },
  },
  components: {
    Loading,
    SelectedCourse,
    SearchModal
  }
}

</script>

<style scoped>

</style>