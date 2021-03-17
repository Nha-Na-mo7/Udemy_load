<template>
  <div class="f_page">
    <h2>講座登録</h2>

    <!-- 紹介したいコースについてのフォーム -->
    <div>
      <form class="p-form" v-on:submit.prevent="submitCourse">
        <label for="record_title">タイトル</label>
        <input type="text" id="record_title" class="p-form__title p-form__item" v-model="createData.recordForm.title" placeholder="タイトルは必須です。">

        <label for="record_description"></label>
        <textarea
            id="record_description"
            class="p-form__description p-form__item c-form__textarea"
            v-model="createData.recordForm.description"
            placeholder="説明文を入力してください。"
        ></textarea>
        <!-- 投稿する -->
        <div>
          <button class="c-btn">投稿する</button>
        </div>
      </form>
    </div>

    <!-- 現在追加されているコース-->
    <div>
      <SelectedCourse
        v-for="(Course, index) in createData.selectedCourses"
        :key="Course.id"
        :course="Course"
        :value="Course.description"
        @input="Course.description = $event"
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
      createData: {
        selectedCourses: [],
        recordForm: {
          title: '',
          description: ''
        },
      }
    }
  },
  methods: {
    // モーダルフラグを切り替え
    toggleModalFlg() {
      this.modalFlg = !this.modalFlg;
    },
    // 選択済みコースにコースオブジェクトを追加する
    pushCourseObjToSelectedCoursesArr(e) {
      this.createData.selectedCourses.push(e);
    },
    // オブジェクトを削除する
    deleteCourseObject(index) {
      this.createData.selectedCourses.splice(index, 1)
    },
    // コースのレコードを投稿する
    async submitCourse() {

      // コースが1つもない場合は警告してfalseを返す
      if (!this.createData.selectedCourses.length) {
        return false
      }

      const response = await axios.post('../records/create', this.createData);

      console.log(response)

      // // バリデーションエラー
      // if (response.status === UNPROCESSABLE_ENTITY) {
      //   this.errors = response.data.errors;
      //   return false
      // }
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