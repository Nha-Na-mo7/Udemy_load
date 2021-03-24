<!--投稿済みレコードの編集-->
<template>
  <div>
    <div v-if="loading">
      <Loading />
    </div>

    <div v-else>
      <h2>編集</h2>
      <div>
        <form>
          <!-- タイトル -->
          <label for="record_title">
            タイトル
            <input
                id="record_title"
                class="c-form__input"
                v-model="updateData.recordForm.title"
            >
          </label>
          <!-- description -->
          <label for="record_description">
            description
            <textarea
                id="record_description"
                class="p-record__edit--textarea c-form__textarea"
                v-model="updateData.recordForm.description"
            ></textarea>
          </label>

          <!-- 現在追加されているコース-->
          <div>
            <EditingCourse
                v-for="(Course, index) in updateData.selectedCourses"
                :key="Course.id"
                :course="Course"
            ></EditingCourse>
          </div>
        </form>

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
    </div>

  </div>
</template>

<script>
import {OK, FORBIDDEN, NOT_FOUND, UNPROCESSABLE_ENTITY} from "../../util";
import Loading from "../../components/Loading";
import EditingCourse from "../Records/EditingCourse";
import SearchModal from './SearchModal.vue';

export default {
  props: {
    id: {
      type: String,
      required: true,
    }
  },
  data() {
    return {
      loading: true,
      modalFlg: false,
      updateData: {
        selectedCourses: [],
        recordForm: {
          title: '',
          description: ''
        }
      },
    }
  },
  methods: {
    // ========================
    // レコードの情報をDBから取得
    // ========================
    async fetchRecord() {
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
      this.updateData.recordForm.title = response.data.title
      this.updateData.recordForm.description = response.data.description
      this.updateData.selectedCourses = response.data.courses

      this.loading = false
    },
    // ===============
    // 削除
    // ===============
    async deleteRecord() {
      const response = await axios.post(`/record/${this.id}/delete`)

      // なんらかの理由で二重削除が行われてしまった時
      if (response.status === NOT_FOUND) {
        this.$router.push('/')
      }

      // エラー時の処理
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false
      }

      // 削除成功したらトップに戻す
      this.$router.push('/')
    },
    // =================
    // 更新処理
    // =================
    async update() {
      const response = await axios.post(``)
    },
    // ==============
    // モーダル切り替え
    // ==============
    toggleModalFlg() {
      this.modalFlg = !this.modalFlg
    },
    // =================
    // モーダルから追加する
    // =================
    pushCourseObjToSelectedCoursesArr(e) {
      this.updateData.selectedCourses.push(e)
    }
  },
  components: {
    Loading,
    EditingCourse,
    SearchModal,
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
.p-record__edit--textarea {
  min-width: 600px;
  min-height: 180px;
}
</style>