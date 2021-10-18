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

application.prototype.init = async function() {
    var p = {
        init: 1
    };
    return new Promise((resolve, reject) => {

        $.ajax({url:'cntr/gwdata.php', type:'POST',data:p, dataType:'json'}).
        done(function(data){
            resolve(data);
        }).
        fail(function(e) { 
            console.log('init - ajax error', e);
            reject(e);
        });  
    });    
}

// .............................................................................

application.prototype.save = async function(data, fname, lastname, fdate, gender, dur, score) {
    //var d =  data.map(el => {return el.answer});
    var fn = Utils.escape_RegExp(fname);
    var ln = Utils.escape_RegExp(lastname);
    var fd = Utils.escape_RegExp(fdate);

    var p = {
        data: data,
        fname: fn,
        lastname: ln,
        fdate: fd,
        gender: gender,
        dur: dur,
        score: score
    };
    return new Promise((resolve, reject) => {

        $.ajax({url:'cntr/gwdata.php', type:'POST',data:p, dataType:'json'}).
        done(function(data){
            resolve(data);
        }).
        fail(function(e) { 
            console.log('save ajax error', e);
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
