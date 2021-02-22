// ====================
// ログインユーザー情報
// ====================

import { OK } from '../util.js';

// ===============
// state
// ===============
const state = () => ({
  user: null,
  // APIの呼び出しが成功したかの判定
  apiStatus: null
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
  },
  // API呼び出し成否をセット
  setApiStatus(state, status) {
    state.apiStatus = status
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
  // ===========
  // ログイン
  // ===========
  async login(context, data) {
    // APIステータスストアをnullでリセット
    context.commit('setApiStatus', null)
    
    const response = await axios.post('login', data)
        .catch(err => err.response || err)
    
    // 呼び出し成功時
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    
    // 失敗時
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },
  // ログアウト
  async logout(context, data) {
    const response = await axios.post('logout', data)
    context.commit('setUser', null)
  },
  // 現在ログイン中のユーザーを取得しセットする
  async currentUser(context) {
    const response = await axios.get('user')
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
