<template>
  <div class="f_page">
    <h2>講座登録</h2>

    <!-- 紹介したいコースについてのフォーム -->
    <div>
      <form class="p-form">
        <label for="record_title">タイトル</label>
        <input type="text" id="record_title" class="p-form__title p-form__item" v-model="recordForm.title" placeholder="タイトルは必須です。">

        <label for="record_description">説明</label>
        <input type="text" id="record_description" class="p-form__description p-form__item" v-model="recordForm.description" placeholder="説明文を入力してください。">
      </form>
    </div>

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
import {CREATED, UNPROCESSABLE_ENTITY} from "../../util";
import Loading from '../../components/Loading.vue';
import SelectedCourse from './SelectedCourse.vue';
import SearchModal from './SearchModal.vue';


export default {
  data() {
    return {
      modalFlg: false,
      selectedCourses: [],
      recordForm: {
        title: '',
        description: ''
      }
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
    // コースのレコードを投稿する
    async submitCourse() {
      const response = await axios.post('../records/create', this.recordForm);

      // バリデーションエラー
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return false
      }

      // 作成完了
      if (response.status !== CREATED) {
        this.$store.commit('error/setErrorCode', response.status)
        return false
      }

      // 投稿後にその詳細ページへ遷移させる
      this.$router.push(`/records/${response.data.id}`)
    }
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