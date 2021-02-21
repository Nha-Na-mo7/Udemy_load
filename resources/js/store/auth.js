// ====================
// ログインユーザー情報
// ====================

// state
// ===============
const state = () => ({
  user: null
});

// ===============
// getter
// ===============
const getters = {
  // ログインチェック
  check: state => !!state.user,
  // ログインユーザーのnameを返し、それがnullの場合は空文字を返す
  username: state => state.user ? state.user.name: ''
};

// ===============
// mutations
// ===============
const mutations = {
  // ユーザーの情報をセット
  setUser(state, user) {
    state.user = user
  }
};

// ===============
// actions
// ===============
const actions = {
  // 会員登録
  async register(context, data) {
    const response = await axios.post('register', data)
    context.commit('setUser', response.data)
  },
  // ログイン
  async login(context, data) {
    const response = await axios.post('login', data)
    context.commit('setUser', response.data)
  },
  // ログアウト
  async logout(context, data) {
    const response = await axios.post('logout', data)
    context.commit('setUser', null)
  },
  // 現在ログイン中のユーザーを取得しセットする
  async currentUser(context) {
    const response = await axios.post('user')
    const user = response.data || null
    context.commit('setUser', user)
  },
};

// ================
// export default
// ================
export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
