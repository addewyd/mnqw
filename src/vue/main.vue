<template>
<div>
    <div class="top">
        Opioid Risk Tool<span v-if="gender"> ({{gender}})</span>
        <br />
        <span v-if="gender">
            [ORT{{gender[0]}}]
        </span>
        <span v-else="">
            [ORT]
        </span>
    </div>    
<div v-if="admin_mode">
    Score: {{score}} ({{gender}})
    <div v-if="showlist" style="margin: 1em">
        <div v-for="f in pdffiles" style="margin: 3 px;">
            <a :href="f[0]">{{f[1]}}</a>
        </div>
    </div>
        <div class="ret">
        <button class="btn-primary mbtn" @click="restart()">Restart</button>
        </div>
</div>
<div v-else="">  
    <div class="progress-wrapper">
    progress
    <div id="progress">
        <span :style="'width:'+(qLen*(state-1))+'%;background-color:#0e0eee;display:inline-block;height:100%;border-radius:6px/50%'"> </span>
    </div>
    </div>
    
    <div v-if="state<1+qLen">
    <div class="inputs" v-if="show_inputs">
        <label for="g_name">Patient </label>
        <input type="text" id="g_name" name="fname" v-model="fname" style="margin: 0 1em;"
            v-validate="'required'"/>
        <span v-if="show_date">
        <label for="g_date">Date </label>
        <input type="date" id="g_date" name="fdate" 
            v-model="fdate" style="margin: 0 1em;width:12em;-webkit-appearance: menulist"/>
        </span>
        <label for="g_one">Female </label>
        <input type="radio" id="g_one" value="Female" v-model="gender" />
        <label for="g_two">Male </label>
        <input type="radio" id="g_two" value="Male" v-model="gender" />
        <button @click="hideinputs()">Hide</button>
    </div>
    <div class="quest">
         {{state}}) {{qData[state-1].text}}
    </div>
    <div class="ynb">
    <div>
        <button :class="qData[state-1].answer=='yes'?'btn-primary mbtn':'btn-secondary mbtn'" 
            @click="fyes()">Yes</button>        
    </div>
    <div>
        <button  :class="qData[state-1].answer=='no'?'btn-primary mbtn':'btn-secondary mbtn'" 
            @click="fno()">No</button>        
    </div>
    </div>
    <div class="pnb">
        <div class="pnbp">
        <button class="btn-primary mbtn" @click="prev(state)">Previous</button>
        </div>
        <div class="pnbn">
        <button class="btn-primary mbtn" @click="nxt(state)">Next</button>
        </div>
        <div class="clear"></div>
    </div>
    </div>
    
    <div v-else="">
        <div class="ret">
        Please return device to your healthcare provider
        </div>
        <div class="ret">
        <button class="btn-primary mbtn" @click="toadmin()">Provider login</button>
        </div>
    </div>

<div  class="logo">
    <img src="img/ORTlogo.png" width="200" />
</div>
</div>
</div>
</template>

    <script>
    
import {app, Vue} from '../app/app';
import * as Utils  from '../app/utils';
export default {
    data: function() {
        return {
            admin_mode: false,
            show_date: false,
            show_inputs: true,
            showlist: false,
            p: '',
            pdffiles: [],
            state: 1,
            fname: '',
            fdate: '',
            gender: '',
            startt: 0,
            endt: 0,
            score: 0,
            qData: [
                {
                    text: 'Family history of substance abuse? Alcohol',
                    answer: false,
                    scoref: 1,
                    scorem: 3
                },
                {
                    text: 'Family history of substance abuse? Illegal drugs',
                    answer: false,
                    scoref: 2,
                    scorem: 3
                },
                {
                    text: 'Family history of substance abuse? Prescription drugs',
                    answer: false,
                    scoref: 4,
                    scorem: 4
                },
                {
                    text: 'Personal history of substance abuse? Alcohol',
                    answer: false,
                    scoref: 3,
                    scorem: 3
                },
                {
                    text: 'Personal history of substance abuse? Illegal drugs',
                    answer: false,
                    scoref: 4,
                    scorem: 4
                },
                {
                    text: 'Personal history of substance abuse? Prescription drugs',
                    answer: false,
                    scoref: 5,
                    scorem: 5
                },
                {
                    text: 'Are you between 16 years old and 45 years old?',
                    answer: false,
                    scoref: 1,
                    scorem: 1
                },
                {
                    text: 'History of preadolescent sexual abuse?',
                    answer: false,
                    scoref: 3,
                    scorem: 0
                },
                {
                    text: 'Psychological Disease:  Do you have a history of ADD, OCD, Bipolar Disease, or Schizophrenia?',
                    answer: false,
                    scoref: 2,
                    scorem: 2
                },
                {
                    text: 'Psychological Disease: Do you have a history of Depression?',
                    answer: false,
                    scoref: 1,
                    scorem: 1
                }
            ],
            qLen: 0
        };
    },
    mounted: async function() {
        this.qLen = this.qData.length;
        var pp = await app.init();
        this.p = pp['result'];
        var d = new Date();
        this.fdate = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate()
    },
    methods: {
        hideinputs: function() {
            if(this.gender === '' || this.fname === '') {
                Vue.dialog.alert('Please type Name and choose gender');
                return;
            }
            this.show_inputs = false;
        },
        prev: function(st) {
            if(st > 1) {
                this.state = st - 1;
            }
        },
        nxt: function(st) {
            if(this.gender === '') {
                Vue.dialog.alert('Please choose your gender');
                return;
            }
            if(this.fname === '') {
                Vue.dialog.alert('Please enter your name');
                return;
            }
            if(this.fdate === '') {
             //   Vue.dialog.alert('Please enter  date');
             //   return;
            }
            

                if(!this.qData[this.state-1].answer) {
                    Vue.dialog.alert('Click Yes or No');
                    return;
                }
                if(st < 2) {
                    this.startt = Math.floor(new Date() / 1000);
                }
                if(st < this.qLen) {
                    this.state = st + 1;
                } else {
                    this.state = this.qLen + 1;
                }
                this.endt = Math.floor(new Date() / 1000);
            
        },
        fyes: function() {
            if (this.state > 0 && this.state < 1 + this.qLen)
                this.qData[this.state-1].answer = 'yes';
        },
        fno: function() {
            if (this.state > 0 && this.state < 1 + this.qLen)
                this.qData[this.state-1].answer = 'no';
        },
        toadmin: async function() {
            var res = await this.login();
            if(! res) {
                return;
            }
            
            var score = this.qData.reduce((a, b) => {
                if(this.gender === 'Male') {
                    return a + (b.answer === 'yes'? b.scorem : 0);
                }
                else if(this.gender === 'Female') {
                    return a + (b.answer === 'yes' ? b.scoref : 0);
                }
                else {
                    return 0;
                }
            }, 0);
            this.score = score;
            var saveresult = await app.save(
                
                    this.qData, 
                    this.fname, 
                    this.fdate,
                    this.gender,
                    this.endt - this.startt,
                    this.score                
            );
            var p = saveresult.files
            this.pdffiles = p.slice(0,30).map(
                a => {
                    var n = a.split('/').pop();
                    
                    return ['saved/'+n, n];
                }
            );
            this.admin_mode = true;
        },
        restart: function() {
            this.admin_mode = false;
            this.show_inputs = true;
            this.state = 1;
            this.fname = '';
            this.fdate = '';
            this.gender = '';
            this.qData.map(d => d.answer = false);
        },
        
        login: function() {
            var res = false;
            //var pstr = '111';
            var pstr = this.p;
            
            return new Promise((resolve, reject) => {
            
            this.$dialog
                .prompt({
                    title: "Log in",
                    body: "Provider password",
                    promptHelp: 'Type it and click "[+:okText]"'
                })
                .then(dialog => {
                    var pp = 'm'+Utils.md5(dialog.data);
                    if(pp == 'm'+pstr) {
                        resolve(true);
                    }
                    else {
                       resolve(false);
                    }
                })
                .catch(() => { 
                    console.log('Prompt dismissed');
                    resolve(false);
                });            
            });            
        }
    }
}
</script>
