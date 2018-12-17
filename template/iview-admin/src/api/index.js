import Axios from 'axios'

import LRU from 'lru-cache'


let base = 'http://www.yhz.com';
// let base = '/webapi';

const inBrowser = typeof window !== 'undefined';

// to attach the cache to the process which is shared across all requests.
const cache = inBrowser
  ? null
  : (process.__API_CACHE__ || (process.__API_CACHE__ = createCache()));

function createCache () {
  return LRU({
    max: 1000,
    maxAge: 1000 * 60 * 15 // 15 min cache
  })
}

// 创建所有API请求服务为单例实例
// const api = createServerSideAPI()
const api = inBrowser
  ? createServerSideAPI()
  : (process.__API__ || (process.__API__ = createServerSideAPI()));

function createServerSideAPI () {
  const api = Axios.create({ baseURL: base});
  return api
}
export { api , base };




