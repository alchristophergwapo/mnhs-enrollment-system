<template>
  <div id="app">
    <v-toolbar color="#E5E5E5">
      <v-toolbar-title>
        <h4>Dashboard</h4>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-icon>mdi-home</v-icon>
      <v-breadcrumbs :items="items"></v-breadcrumbs>
    </v-toolbar>
    <br />
    <br />
    <br />
    <v-col sm="6" md="4">
      <v-card class="mx-auto" color="#26c6da" dark max-width="300">
        <v-card-text class="headline font-weight-bold">
            <v-avatar class="ma-3" size="125" tile>
            <img src="/images/student.png" alt="" />
          </v-avatar>
          <div id="text">
            <span class="title font-weight-bold">{{Students}}</span>
            <br />
            <span class="title font-weight-light">Students</span>
          </div>
        </v-card-text>
        <v-btn id="btn" block link to="/admin/all_students">
            All Students
            <v-icon dark right>mdi-arrow-right</v-icon>
        </v-btn>
      </v-card>
    </v-col>

    <v-col sm="6" md="4">
      <v-card class="mx-auto" color="#26c6da" dark max-width="300">
        <v-card-text class="headline font-weight-bold">
          <v-avatar class="ma-3" size="125" tile>
            <img src="/images/teachers.png" alt="" />
          </v-avatar>
          <div id="text">
            <span class="title font-weight-bold">{{Teachers}}</span>
            <br />
            <span class="title font-weight-light">Teachers</span>
          </div>
        </v-card-text>
        <v-btn id="btn" block link to="/admin/all_teachers">
            All Teachers
            <v-icon dark right>mdi-arrow-right</v-icon>
        </v-btn>
      </v-card>
    </v-col>

    <v-col sm="6" md="4">
      <v-card class="mx-auto" color="#26c6da" dark max-width="300">
        <v-card-text class="headline font-weight-bold">
          <v-avatar class="ma-3" size="125" tile>
            <img src="/images/section.png" alt="" />
          </v-avatar>
          <div id="text">
            <span class="title font-weight-bold">{{Sections}}</span>
            <br />
            <span class="title font-weight-light">Sections</span>
          </div>
        </v-card-text>
            <v-btn id="btn" block link to="/admin/all_sections">
            All Sections
            <v-icon dark right>mdi-arrow-right</v-icon>
        </v-btn>
      </v-card>
    </v-col>
  </div>
</template>



<script>
export default {
  data: () => ({
    Students:"40",
    Teachers:"15",
    Sections:"5",
    items: [
      {
        text: "Home",
        disabled: false,
        href: "/admin",
      },
      {
        text: "Dashboard",
        disabled: true,
        href: "breadcrumbs_link_2",
      },
    ],
   
  }),
};
</script>



<style>
#btn:hover,
#btn:focus {
  text-decoration: none;
}

#students {
  width: 30%;
}

#text {
  float: right;
  margin-right: 35px;
  margin-top: 40px;
  text-align-last: right;
}
</style>