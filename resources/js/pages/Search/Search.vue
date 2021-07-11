<!--==========-->
<!--検索用ページ-->
<!--==========-->
<template>
  <div class="p-search">
    <div class="p-search__container">
      <div class="p-search__navigation">
        <form action="" class="p-search__form c-form">
          <input
              type="text"
              class="c-form__input c-form__input--recordSearch"
              v-model="searchParams.q"
          >
          <button
              type="submit"
              @click.prevent="searchRecords()"
              class="p-search__btn c-btn"
          >
            <i class="fas fa-search c-icon__fa--default"></i> 検索
          </button>
        </form>
        <!-- search sort -->
        <div class="p-search__sort c-select__container">
          <label>
            <select required name="sort" class="c-select c-select__fs--sort">
              <option class="" value disabled>並べ替え</option>
              <option class="" value="created" @select="changeParamSort('created')">新着順</option>
              <option class="" value="old" @select="changeParamSort('old')">古い順</option>
            </select>
          </label>
          <!-- ▼ -->
          <div class="c-select__allow--container">
            <i class="fas fa-caret-down c-select__allow c-select__allow--fs"></i>
          </div>
        </div>
      </div>

      <!-- 検索結果一覧 -->
      <div id="searchResult" class="p-search__result--container">
        <div v-if="isSearching">
          <Loading />
        </div>

        <!-- エラー時 -->
        <div v-else-if="errors" class="c-modal__error">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p class="c-error">{{ errors }}</p>
        </div>

        <!-- 検索結果がない場合 -->
        <div v-else-if="this.isNothingResult" class="c-modal__nothing">
          <i class="fas fa-exclamation-circle c-modal__nothing--fa"></i>
          <p>{{ this.isResultDescription }}</p>
        </div>

        <!-- 結果コンポーネント一覧 -->
        <Result
            v-else-if="this.records"
            v-for="Record in records"
            :key="Record.id"
            :item="Record"
        />

        <!-- pagination -->
        <div>
          <h2>ここにpaginationが入ります</h2>
          <button class="c-btn">前へ</button>
          <button class="c-btn">次へ</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
/*
 * 早見表
 * 新着順:created
 * 古い順:old
 */
import Loading from '../../components/Loading.vue';
import Result from './Result.vue';
import {OK} from "../../util";
export default {
  data() {
    return {
      isSearching: true,
      isNothingResult: false,
      resultDescription: '',
      sortState: 1,
      errors: '',
      searchParams: {
        sort: '',
        q: '',
      },
      records: []
    }
  },
  computed: {
    isResultDescription() {
      return this.resultDescription
    },
    paramQuery() {
      return this.$route.query.q
    },
    paramSort() {
      return this.$route.query.sort
    },
    checkExistParamQuery() {
      return typeof this.paramQuery === 'undefined' || this.paramQuery === ''
    },
  },
  methods: {
    // ロードマップ一覧の取得
    async fetchCourse() {
      // クエリがない場合
      if(this.checkExistParamQuery) {
        this.resultDescription = '検索ワードが入力されていません。'
        this.isNothingResult = true
        this.isSearching = false
        return false
      }

      this.isNothingResult = false;
      this.isSearching = true;

      const params = this.$route.query;
      const response = await axios.get(`/records/search`, { params });

      // エラー時
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }
      this.records = response.data;

      // 検索結果が0件の場合
      if (response.data.length === 0) {
        this.resultDescription = '検索ワードに一致するロードマップはありませんでした。'
        this.isNothingResult = true;
      }
      this.isSearching = false;
    },
    changeParamSort(sortWord) {
      // GETパラメータの作成
      switch (sortWord) {
        case 'created':
          this.searchParams.sort = 'created'
          break;
        case 'old':
          this.searchParams.sort = 'old'
          break;
        default:
          this.searchParams.sort = ''
      }
    },
    searchRecords() {
      this.$router.push({
          query: this.searchParams,
        }
      ).catch(err => {})
      // preventDefault後はfetchCourseが働かないのでここでfetchする
      this.fetchCourse()
      this.updateTitle()
    },
    // ページタイトルの更新
    updateTitle() {
      // クエリの有無によるtitleの変更
      const title = this.checkExistParamQuery ? '検索' : `${this.paramQuery} の検索結果`
      document.title = `${title} |  UdemyLoad`
    },
  },
  components: {
    Loading,
    Result,
  },
  mounted() {
    // 検索結果の取得
    this.fetchCourse()
    this.changeParamSort(this.paramSort)
    this.searchParams.q = this.paramQuery
    this.updateTitle()
  },
}
</script>
<style scoped>
</style>