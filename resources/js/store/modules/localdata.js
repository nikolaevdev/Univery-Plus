import axios from 'axios'
import LocalStorage from 'localstorage';
import * as types from '../mutation-types';

// state
export const state = {
  localdata: new LocalStorage('app')
}

// getters
export const getters = {
  localdata: state => state.localdata,
}

// mutations
export const mutations = {
  [types.SET_LOCALDATA] (state, { name, data }) {
    state.localdata.put(name, data)
  },
  [types.GET_LOCALDATA] (state, { name }) {
    state.localdata.get(name)
  },
  [types.HAS_LOCALDATA] (state, { name }) {
    state.localdata.has(name)
  },
  [types.DELETE_LOCALDATA] (state, { name }) {
    state.localdata.delete(name)
  },
  [types.DELETE_ALL_LOCALDATA] (state, {}) {
    state.localdata.delete()
  }
}

// actions
export const actions = {
  set ({ commit }, { name, data }) {
    commit(types.SET_LOCALDATA, { name, data })
  },
  get ({ commit }, { name }) {
    commit(types.GET_LOCALDATA, { name })
  },
  has ({ commit }, { name }) {
    commit(types.HAS_LOCALDATA, { name })
  },
  delete ({ commit }, { name }) {
    commit(types.DELETE_LOCALDATA, { name })
  },
  delete_all ({ commit }, { }) {
    commit(types.DELETE_ALL_LOCALDATA, { })
  }
}