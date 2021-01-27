<template>
  <div class="enrollment">
    <v-toolbar dark color="#3bbdda">
      <v-avatar>
        <v-img src="images/logo.jpg"></v-img>
      </v-avatar>
      <v-toolbar-title>Welcome to Mantalongon, Dalaguete NHS</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn text>Login</v-btn>
    </v-toolbar>
    <br /><br />
    <div class="form-container">
      <v-card
        color="rgba(255, 255, 255, .8)"
        class="card"
        elevation="2"
        max-width="800"
      >
        <div class="form-header">
          Please fill out the information below and SUBMIT.
        </div>
        <v-form v-model="valid">
          <v-container>
            <student-info-form></student-info-form>
            <parent-guardian-info></parent-guardian-info>
            <balik-or-transfer></balik-or-transfer>
            <senior-high :tracks="tracks"></senior-high>
            <center>
              <div class="enroll-date">
                <v-text-field type="date" label="Date"></v-text-field>
              </div>
            </center>
          </v-container>
        </v-form>
      </v-card>
    </div>
  </div>
</template>

<script>
import StudentInfoForm from "./enrollment/StudentInfoForm.vue";
import ParentGuardianInfo from "./enrollment/ParentGuardianInfo.vue";
import BalikOrTransfer from "./enrollment/BalikOrTransfer.vue";
import SeniorHigh from "./enrollment/SeniorHigh.vue";

export default {
  components: {
    StudentInfoForm,
    ParentGuardianInfo,
    BalikOrTransfer,
    SeniorHigh,
  },
  data() {
    return {
      valid: false,
      tracks: ["Track 1", "Track 2", "Track 3", "Track 4"],
      strands: ["Strand 1", "Strand 2", "Strand 3", "Strand 4"],
    };
  },
  mounted: () => {
  }
};
</script>

<style>
.card {
  position: absolute;
  right: 20px;
  margin-left: 20px;
}
.v-messages__message {
  color: red;
}
</style>