<template>
<div class="container">
<div v-if="admin_mode">
        <div>
        <button class="btn-primary mbtn" @click="restart()">Restart</button>
        </div>
</div>
<div v-else>    
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
    
    <div v-if="state<11">
    <div>
        Name: <input type="text" name="fname" v-model="fname" v-validate="'required'"/>        
        Date: <input type="date"  name="fdate" 
            v-model="fdate" style="-webkit-appearance: menulist"/>
        Gender: 
        <input type="radio" id="g_one" value="Female" v-model="gender" />
            <label for="g_one">Female</label>
        <input type="radio" id="g_two" value="Male" v-model="gender" />
        <label for="g_two">Male</label>

    <span>Selected: {{ gender }}</span>
        
        
    </div>
    <div class="quest">
        Question {{state}} {{qData[state-1].text}}
    </div>
    <div class="ynb">
    <div>
        <button :class="qData[state-1].answer=='yes'?'btn-primary mbtn':'btn-secondary mbtn'" @click="fyes()">Yes</button>        
    </div>
    <div>
        <button  :class="qData[state-1].answer=='no'?'btn-primary mbtn':'btn-secondary mbtn'" @click="fno()">No</button>        
    </div>
    </div>
    <button class="btn-primary mbtn" @click="prev(state)">Previous</button>
    <button class="btn-primary mbtn" @click="nxt(state)">Next</button>
    </div>
    <div v-else>
        END!!
        Return DEVICE!!
        <div>
        <button class="btn-primary mbtn" @click="toadmin()">Admin</button>
        </div>
    </div>

<div style="text-align: center">
    <img src="img/ORTlogo.png" width="200" />
</div>
</div>
</div>
</template>

    <script>
    
import {app} from '../app/app';
    
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
            if(!this.qData[this.state-1].answer) {
                alert('Click Yes or No');
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
        toadmin: function() {
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
            this.qData.map(d => d.answer = false);
        }
    }
}
</script>
