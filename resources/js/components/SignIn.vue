<template>
  <div id="app">
        <v-app>
            <v-container class="container">
                <v-card class="card1"   elevation="5" shaped> 
                    <center><img src="/images/enroll.png" alt="" id="icons"/></center>
                    <h3>MNHS Enrollment System</h3><br>
                        <v-tabs  v-model="tab" show-arrows background-color="light-blue"  icons-and-text dark grow  >
                                <v-tabs-slider color="grey darken-4"></v-tabs-slider>
                                <v-tab v-for="i in tabs" :key="i.name" padding="20">
                                    <br>
                                    <v-icon large>{{i.icon}}</v-icon>
                                    <div class="caption py-1">{{i.name}}</div>
                                </v-tab>
                                <v-tab-item>
                                    <v-card>
                                    <v-card-text>
                                        <v-form ref="regStudentForm" v-model="valid" lazy-validation>
                                        <v-row>
                                            <v-col cols="12">
                                            <div class="font-weight-bold">
                                                <h4>Learner's Reference No. (LRN)</h4>
                                            </div>
                                            <v-text-field v-model="lrn" :rules="[rules.lrn]" maxlength="20" required></v-text-field>
                                            </v-col>
                                            <v-spacer></v-spacer>

                                            <v-col cols="12">
                                            <div class="font-weight-bold">
                                                <h4>Password</h4>
                                            </div>
                                            <v-text-field
                                                id="pwd"
                                                v-model="password"
                                                :append-icon="show1? 'mdi-eye' : 'mdi-eye-off'"
                                                :rules="[rules.password, rules.min]"
                                                :type="show1 ? 'text' : 'password'"
                                                name="input-10-1"
                                                hint="At least 8 characters"
                                                @click:append="show1 = !show1"
                                            ></v-text-field>
                                            </v-col>
                                            <v-spacer></v-spacer>
                                            <v-col class="d-flex ml-auto">
                                            <v-btn x-large block :disabled="!valid" color="info" @click="student">
                                                <h4>Sign In</h4>
                                            </v-btn>
                                            </v-col>
                                        </v-row>
                                        </v-form>
                                    </v-card-text>
                                        </v-card>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card >
                                    <v-card-text>
                                        <v-form ref="regAdminForm" v-model="advalid" lazy-validation >
                                        <v-row>
                                            <v-col cols="12">
                                            <div class="font-weight-bold">
                                                <h4>Username</h4>
                                            </div>
                                            <v-text-field v-model="adminName" :rules="[adrules.username]" maxlength="20" required></v-text-field>
                                            </v-col>
                                            <v-spacer></v-spacer>

                                            <v-col cols="12">
                                            <div class="font-weight-bold">
                                                <h4>Password</h4>
                                            </div>
                                            <v-text-field
                                                id="pwd"
                                                v-model="adminPass"
                                                :append-icon="adminshow? 'mdi-eye' : 'mdi-eye-off'"
                                                :rules="[adrules.addpwd,adrules.min]"
                                                :type="adminshow? 'text' : 'password'"
                                                name="input-10-1"
                                                hint="At least 8 characters"
                                                @click:append="adminshow = !adminshow"
                                            ></v-text-field>
                                            </v-col>
                                            <v-spacer></v-spacer>
                                            <v-col class="d-flex ml-auto">
                                            <v-btn x-large block :disabled="!advalid"   color="info" @click="admin" >
                                                <h4>Sign In</h4>
                                            </v-btn>
                                            </v-col>
                                        </v-row>
                                        </v-form>
                                    </v-card-text>
                                    </v-card>
                                </v-tab-item>
                        </v-tabs>
                       
                </v-card>
            </v-container>   
        </v-app>
  </div>
</template>


<script>
export default {
  data: () => ({

    dialog: true,
    tab: 0,

    tabs: [
      { name: "Login as Enrollee",icon:"mdi-account"},
      { name: "Login as Admin",icon:"mdi-account-outline"}
    ],
   
    valid: true,
    advalid:true,
    lrn: "",
    password: "",

    adminName:"",
    adminPass:"",
    show1: false,
    adminshow: false,

    rules: {
      lrn: value => !!value || "Learner's Reference No. is required!",
      password: value => !!value || "Password is required!",
      min: v => (v && v.length >= 8) || "Min 8 characters"
    },

      adrules: {
      username: value => !!value || "Username is required!",
      addpwd: value => !!value || "Password is required!",
      min: v => (v && v.length >= 8) || "Min 8 characters"
    }

  }),

  computed: {},

  methods: {
    
    student(){

      if (this.$refs.regStudentForm.validate()) {
        // submit form to server/API here...
        alert("This is for testing!")
        this.$refs.regStudentForm.reset()
      }

    },


   admin(){
     if(this.$refs.regAdminForm.validate()) {
        //submit form to server/API here...
      }

   },

    resetValidation(){
      this.$refs.form.resetValidation();
    }

  }


};


</script>


<style>


/* #app{
    background-color: rgb(57, 151, 206);
} */

.card1{
    padding: 2%;
}


#pwd {
  width: 1px;
  height: 10px;
}

.img1{
    width: 400px;
    height: 250px;
    margin-top: 15px;
}


h3{
    font-size: 25px;
    letter-spacing: 1.5;
    text-align: center;
    color: rgb(46, 118, 160);
    font-weight: bolder;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container{
    width: 50%;
    margin-top: 8%;
}


#icons{
    width: 120px;
    height:120px;
}

</style>