import Vue from 'vue';
import $ from 'jquery';
import * as Utils  from './utils';
import en from 'vee-validate/dist/locale/en';
import VeeValidate, { Validator } from 'vee-validate';
Vue.use(VeeValidate);
Validator.localize('en', en);

import VuejsDialog from "vuejs-dialog" 
Vue.use(VuejsDialog)


import MainTop from '../vue/main.vue';


function application() {
    this.dbname = undefined;
};

var app = new application();

// .............................................................................

application.prototype.save = async function(data, fname, fdate, gender) {
    //var d =  data.map(el => {return el.answer});
    var fn = Utils.escape_RegExp(fname);
    var fd = Utils.escape_RegExp(fdate);
    var p = {
        data: data,
        fname: fn,
        fdate: fd,
        gender: gender
    };
    console.log('save', p);
    return new Promise((resolve, reject) => {

        $.ajax({url:'cntr/gwdata.php', type:'POST',data:p, dataType:'json'}).
        done(function(data){
            console.log(data);
            resolve(data['result']);
        }).
        fail(function(e) { 
            console.log('ajax error', e);
            reject(e);
        });  
    });
};

// .............................................................................

new Vue({
  el: '#app-main-top',
  components: {
    'app-main-top': MainTop
  }
});


export /*default*/ {app, Vue};
