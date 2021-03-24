<!-- レコードの新規投稿 / 編集用 -->
<template>
  <div class="f_page">
    <h2 v-if="isCreateMode">講座の新規登録</h2>
    <h2 v-else>編 集</h2>

    <!-- 紹介したいコースについてのフォーム -->
    <div>
      <form class="p-form" v-on:submit.prevent="submitCourse">
        <label for="record_title">タイトル</label>
        <input
            type="text"
            id="record_title"
            class="p-form__title p-form__item"
            v-model="createData.recordForm.title"
            placeholder="タイトルは必須です。"
        >

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
        :createflg="isCreateMode"
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
import {CREATED, FORBIDDEN, OK, UNPROCESSABLE_ENTITY} from "../../util";
import Loading from '../../components/Loading.vue';
import SelectedCourse from './SelectedCourse.vue';
import SearchModal from './SearchModal.vue';

export default {
  // このpropsはレコードの編集時のみ受け取る
  props: {
    id: {
      type: String,
    }
  },
  data() {
    return {
      loading: true,
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
  computed: {
    isCreateMode() {
      return this.id === undefined
    }
  },
  methods: {
    // レコードの情報をDBから取得(編集時のみ)
    async fetchRecord() {
      if (!this.isCreateMode) {
        // レコード情報を取得
        const response = await axios.get(`/record/${this.id}/${true}`)

        // オーナー以外が閲覧した場合(403)、詳細画面に戻す
        if (response.status === FORBIDDEN) {
          this.$router.push(`/records/${this.id}`);
          return false
        }

        // エラー時の処理
        if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status);
          return false
        }
        console.log(response.data)
        // 格納
        this.createData.recordForm.title = response.data.title
        this.createData.recordForm.description = response.data.description
        this.createData.selectedCourses = response.data.courses
      }
      this.loading = false
    },
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
    // コースのレコードを投稿 or 上書きする
    async submitCourse() {

      // コースが1つもない場合は警告してfalseを返す
      if (!this.createData.selectedCourses.length) {
        return false
      }

      // 新規作成か更新かで処理先を分ける
      if (this.isCreateMode) {
        const response = await axios.post(`../records/create`, this.createData);

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

      }else{
        const response = await axios.post(`../record/${this.id}/create`, this.createData);

        if (response.status !== OK) {
          this.$store.commit('error/setErrorCode', response.status)
          return false
        }
        // 詳細ページへ戻す
        this.$router.push(`/records/${this.id}`)
      }
    }
  },
  components: {
    Loading,
    SelectedCourse,
    SearchModal
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchRecord()
      },
      immediate: true
    }
  }
}

</script>

<style scoped>

</style>