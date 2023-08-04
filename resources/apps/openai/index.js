import vuedraggable from 'vuedraggable';
import ImagePicker from "../lib/ImagePicker";
import LocationPicker from "../lib/LocationPicker";
import VueEditor from "../lib/VueEditor";
import MainApp from './common/MainApp';

Vue.component('vuedraggable', vuedraggable);
Vue.component('image-picker', ImagePicker);
Vue.component('location-picker', LocationPicker);
Vue.component('vue-editor', VueEditor);

let API_URL = '/api/';
let getApi = (path) => {
    return API_URL + path;
}

let httpGet = (path, params = {}, config = {}) => {
    return new Promise((resolve, reject) => {
        if (params) config.params = params;
        axios.get(API_URL + path, config).then(response => {
            if (response.data.errCode) {
                if (response.data.errCode === 401) {
                    window.location.reload();
                }
                reject(response.data);
            } else {
                resolve(response.data);
            }
        }).catch(reason => {
            if (reason.statusCode === 401){
                window.location.reload();
            }
            reject(reason);
        });
    });
};

let httpPost = (path, data = {}, config = {}) => {
    return new Promise((resolve, reject) => {
        axios.post(API_URL + path, data, config).then(response => {
            if (response.data.errCode) {
                if (response.data.errCode === 401) {
                    window.location.reload();
                }
                reject(response.data);
            } else {
                resolve(response.data);
            }
        }).catch(reason => {
            if (reason.statusCode === 401){
                window.location.reload();
            }
            reject(reason);
        });
    });
};

Vue.prototype.$get = httpGet;
Vue.prototype.$post = httpPost;
Vue.prototype.$getApi = getApi;

window._ = require('lodash');

const router = require('./router');
const store = require('./store');

new Vue({
    router,
    store,
    render(h) {
        return h(MainApp);
    }
}).$mount('#app');
