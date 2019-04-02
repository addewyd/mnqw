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
    <div style="margin: 1em">
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
        <span :class="state<1?'progress pl':'progress pl progress2'" id="spp0"> </span>
        <span :class="state<2?'progress':'progress progress2'" id="spp1"> </span>
        <span :class="state<3?'progress':'progress progress2'" id="spp2"> </span>
        <span :class="state<4?'progress':'progress progress2'" id="spp3"> </span>
        <span :class="state<5?'progress':'progress progress2'" id="spp4"> </span>
        <span :class="state<6?'progress':'progress progress2'" id="spp5"> </span>
        <span :class="state<7?'progress':'progress progress2'" id="spp6"> </span>
        <span :class="state<8?'progress':'progress progress2'" id="spp7"> </span>
        <span :class="state<9?'progress':'progress progress2'" id="spp8"> </span>
        <span :class="state<10?'progress pr':'progress pr progress2'" id="spp9""> </span>
       
    </div>
    </div>
    
    <div v-if="state<11">
    <div class="inputs">
        Name <input type="text" name="fname" v-model="fname" v-validate="'required'"/>        
        Date <input type="date"  name="fdate" 
            v-model="fdate" style="width:12em;-webkit-appearance: menulist"/>
                <input type="radio" id="g_one" value="Female" v-model="gender" />
            <label for="g_one">Female</label>
        <input type="radio" id="g_two" value="Male" v-model="gender" />
        <label for="g_two">Male</label>
                   
    </div>
    <div class="quest">
         {{state}}) {{qData[state-1].text}}
    </div>
    <div class="ynb">
    <div>
        <button :class="qData[state-1].answer=='yes'?'btn-primary mbtn':'btn-secondary mbtn'" @click="fyes()">Yes</button>        
    </div>
    <div>
        <button  :class="qData[state-1].answer=='no'?'btn-primary mbtn':'btn-secondary mbtn'" @click="fno()">No</button>        
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
    
export default {
    data: function() {
        return {
            admin_mode: false,
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
                    text: 'Family history of substance abuse between 16 years old and 45 years old?',
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
                    text: 'Psychologic Desease: ADD, OCD, bipolar, schizophrenia',
                    answer: false,
                    scoref: 2,
                    scorem: 2
                },
                {
                    text: 'Psychologic Desease: Depression',
                    answer: false,
                    scoref: 1,
                    scorem: 1
                }
            ]
        };
    },
    methods: {
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
                Vue.dialog.alert('Please enter  date');
                return;
            }
            

                if(!this.qData[this.state-1].answer) {
                    Vue.dialog.alert('Click Yes or No');
                    return;
                }
                if(st < 2) {
                    this.startt = Math.floor(new Date() / 1000);
                    console.log('s', this.startt);
                }
                if(st < 10) {
                    this.state = st + 1;
                } else {
                    this.state = 11;
                }
                this.endt = Math.floor(new Date() / 1000);
                console.log('e', this.endt);
            
        },
        fyes: function() {
            if (this.state > 0 && this.state < 11)
                this.qData[this.state-1].answer = 'yes';
                
            // console.log(this.qData);
        },
        fno: function() {
            if (this.state > 0 && this.state < 11)
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
            console.log('score', score);
            this.score = score;
            var saveresult = await app.save(
                
                    this.qData, 
                    this.fname, 
                    this.fdate,
                    this.gender,
                    this.endt - this.startt                
            );
            var p = saveresult.files
            this.pdffiles = p.slice(0,30).map(
                a => {
                    var n = a.split('/').pop();
                    //return '<a href="' + a + '">' + n + '</a>';
                    return ['saved/'+n, n];
                }
            );
            console.log('p', this.pdffiles);
            this.admin_mode = true;
        },
        restart: function() {
            this.admin_mode = false;
            this.state = 1;
            this.fname = '';
            this.fdate = '';
            this.gender = '';
            this.qData.map(d => d.answer = false);
        },
        
        login: function() {
            var res = false;
            var pstr = '111';
            
            return new Promise((resolve, reject) => {
            
            this.$dialog
                .prompt({
                    title: "Log in",
                    body: "Provider password",
                    promptHelp: 'Type it and click "[+:okText]"'
                })
                .then(dialog => {
                    if(
                        dialog.data === pstr) {
                        //console.log('L2');
                        resolve(true);
                    }
                    else {
                       resolve(false);
                    }
                })
                .catch(() => { 
                    console.log('Prompt dismissed');
                    reject(false);
                });            
            });            
        }
    }
}
</script>
