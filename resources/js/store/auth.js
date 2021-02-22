// ====================
// ログインユーザー情報
// ====================

import { OK, UNPROCESSABLE_ENTITY } from '../util.js';

// ===============
// state
// ===============
const state = () => ({
  user: null,
  // APIの呼び出しが成功したかの判定
  apiStatus: null,
  // 新規登録時のエラーメッセージ
  registerErrorMessages: null,
  // ログイン時のエラーメッセージ
  loginErrorMessages: null,
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
  },
  // 新規登録エラーメッセージをセット
  setRegisterErrorMessages(state, messages) {
    state.registerErrorMessages = messages
  },
  // ログインエラーメッセージをセット
  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages = messages
  },
};

// ===============
// actions
// ===============
const actions = {
  // ==========
  // 会員登録
  // ==========
  async register(context, data) {
    // APIステータスストアをnullでリセット
    context.commit('setApiStatus', null)
    
    const response = await axios.post('register', data)
    
    // 新規登録成功時
    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    
    // 失敗時、apiStatusをfalseにセット
    context.commit('setApiStatus', false)
    // バリデーションエラー時
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  // ===========
  // ログイン
  // ===========
  async login(context, data) {
    context.commit('setApiStatus', null)
    
    const response = await axios.post('login', data)
    
    // ログイン成功時
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    
    // 失敗時、apiStatusをfalseにセット
    context.commit('setApiStatus', false)
    // バリデーションエラー時
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, { root: true })
    }
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
