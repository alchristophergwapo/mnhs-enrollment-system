<template>
  <v-row>
    <div class="form-head">Student information</div>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="firstname"
        :rules="nameRules"
        :counter="10"
        label="PSA Birth Certificate No."
        required
      ></v-text-field>
    </v-col>

    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="lastname"
        :rules="nameRules"
        :counter="10"
        label="Learners Reference No. (LRN)"
        required
      ></v-text-field>
    </v-col>

    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="email"
        type="number"
        label="Average"
        required
      ></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="firstname"
        label="Firstname"
        required
      ></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="lastname"
        label="Middlename"
        required
      ></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field v-model="lastname" label="Lastname" required></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="lastname"
        label="Date of Birth"
        type="date"
        required
      ></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="lastname"
        label="Age"
        type="number"
        required
      ></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="2" md="2" lg="2">
      <v-checkbox
        v-model="male_checkbox"
        value="Male"
        label="Male"
        type="checkbox"
        required
      ></v-checkbox>
      <v-spacer></v-spacer>
    </v-col>
    <v-col cols="12" xs="6" sm="2" md="2" lg="2">
      <v-checkbox
        v-model="female_checkbox"
        value="Female"
        label="Female"
        type="checkbox"
        required
      ></v-checkbox>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <p>
        Belonging to any Indegenous People (IP) Community/Indegenous Cultural
        Community?
      </p>
    </v-col>
    <v-col cols="12" xs="6" sm="2" md="2" lg="2">
      <v-checkbox
        v-model="ip_checkbox"
        value="Yes"
        label="Yes"
        type="checkbox"
        required
      ></v-checkbox>
    </v-col>
    <v-col cols="12" xs="6" sm="2" md="2" lg="2">
      <v-checkbox
        v-model="not_ip_checkbox"
        value="No"
        label="No"
        type="checkbox"
        required
      ></v-checkbox>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <v-text-field
        v-model="lastname"
        label="If yes, please specify"
        required
      ></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="6" md="6" lg="6">
      <v-text-field label="Mother Tongue" required></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="6" md="6" lg="6">
      <v-text-field label="Contact Number" required></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="6" md="6" lg="6">
      <v-text-field label="Address" required></v-text-field>
    </v-col>
    <v-col cols="12" xs="6" sm="4" md="4" lg="4">
      <label for="zip_code1">Zip Code &nbsp;&nbsp;&nbsp;</label>
      <input
        type="number"
        class="zipcode"
        pattern="[0-9]*"
        id="zip_code1"
        name="zip_code1"
      />
      <input
        type="number"
        class="zipcode"
        pattern="[0-9]*"
        id="zip_code2"
        name="zip_code2"
      />
      <input
        type="number"
        class="zipcode"
        pattern="[0-9]*"
        id="zip_code3"
        name="zip_code3"
      />
      <input
        type="number"
        class="zipcode"
        pattern="[0-9]*"
        id="zip_code4"
        name="zip_code4"
      />
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: () => ({
    firstname: "",
    lastname: "",
    nameRules: [
      (v) => !!v || "Name is required",
      (v) => v.length <= 10 || "Name must be less than 10 characters",
    ],
    email: "",
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) => /.+@.+/.test(v) || "E-mail must be valid",
    ],
    male_checkbox: null,
    female_checkbox: null,
    not_ip_checkbox: null,
    ip_checkbox: null,
  }),
};
</script>