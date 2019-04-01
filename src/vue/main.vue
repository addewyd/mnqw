<template>
<div>
<div v-if="admin_mode">
        <div>
        <button class="btn-primary mbtn" @click="restart()">Restart</button>
        </div>
</div>
<div v-else="">  
    <div class="progress-wrapper">
    progress
    <div id="progress">
        <span :class="state<2?'progress':'progress progress2'" id="spp0"> </span>
        <span :class="state<3?'progress':'progress progress2'" id="spp1"> </span>
        <span :class="state<4?'progress':'progress progress2'" id="spp2"> </span>
        <span :class="state<5?'progress':'progress progress2'" id="spp3"> </span>
        <span :class="state<6?'progress':'progress progress2'" id="spp4"> </span>
        <span :class="state<7?'progress':'progress progress2'" id="spp5"> </span>
        <span :class="state<8?'progress':'progress progress2'" id="spp6"> </span>
        <span :class="state<9?'progress':'progress progress2'" id="spp7"> </span>
        <span :class="state<10?'progress':'progress progress2'" id="spp8"> </span>
        <span :class="state<11?'progress':'progress progress2'" id="spp9""> </span>
       
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
        
        Plese return device to your healthcare provider
        <div>
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
            state: 1,
            fname: '',
            fdate: '',
            gender: '',
            qData: [
                {
                    text: 'Family history of substance abuse? Alcohol',
                    answer: false
                },
                {
                    text: 'Family history of substance abuse? Illegal drugs',
                    answer: false
                },
                {
                    text: 'Family history of substance abuse? Prescription drugs',
                    answer: false
                },
                {
                    text: 'Personal history of substance abuse? Alcohol',
                    answer: false
                },
                {
                    text: 'Personal history of substance abuse? Illegal drugs',
                    answer: false
                },
                {
                    text: 'Personal history of substance abuse? Prescription drugs',
                    answer: false
                },
                {
                    text: 'Family history of substance abuse between 16 years old and 45 years old?',
                    answer: false
                },
                {
                    text: 'History of preadolescent sexual abuse?',
                    answer: false
                },
                {
                    text: 'Psychologic Desease: ADD, OCD, bipolar, schizophrenia',
                    answer: false
                },
                {
                    text: 'Psychologic Desease: Depression',
                    answer: false
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
                if(st < 10) {
                    this.state = st + 1;
                } else {
                    this.state = 11;
                }
            
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
            var saveresult = app.save(
                
                    this.qData, 
                    this.fname, 
                    this.fdate,
                    this.gender
                
            );
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
            console.log('L1');
            
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
                        console.log('L2');
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
