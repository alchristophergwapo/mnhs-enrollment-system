<template>
  <div>
    <div>
      <v-toolbar color="#E5E5E5">
        <v-toolbar-title><h4>Enrollment</h4></v-toolbar-title>

        <v-spacer></v-spacer>
        <v-icon>mdi-home</v-icon>
        <v-breadcrumbs :items="items"> </v-breadcrumbs>
      </v-toolbar>
    </div>
    <br /><br />
    <div>
      <v-card-title>
        Sort By&nbsp;&nbsp;
        <v-select
          :items="grade_level"
          menu-props="auto"
          label="All"
          hide-details
          dense
          outlined
        ></v-select>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="students"
        :search="search"
        :items-per-page="10"
        class="elevation-1"
      >
        <template v-slot:item="row">
          <tr>
            <td>{{ row.item.grade_level }}</td>
            <td>{{ row.item.student }}</td>
            <td>
              <v-dialog transition="dialog-top-transition" max-width="600">
                <template v-slot:activator="{ on, attrs }">
                  <span class="btn" v-bind="attrs" v-on="on">View Details</span>
                </template>
                <template v-slot:default="dialog">
                  <v-card>
                    <v-card-title>
                      <v-spacer></v-spacer>
                      <v-btn icon @click="dialog.value = false">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-card-title>
                    <v-card-text>
                      <v-row>
                        <v-col cols="12" md="6" sm="6">
                          PSA No.:&nbsp;&nbsp;<strong>123-45-678910</strong>
                        </v-col>
                        <v-col cols="12" md="6" sm="6">
                          LRN:&nbsp;&nbsp;<strong>30300123456</strong>
                        </v-col>
                        <v-col cols="12" md="6" sm="6">
                          Average:&nbsp;&nbsp;<strong>95</strong>
                        </v-col>
                        <v-col cols="12" md="6" sm="6">
                          Full Name:&nbsp;&nbsp;<strong>{{ row.item.student }}</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Birth Date:&nbsp;&nbsp;<strong>January 1, 1999</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="2">
                          Age:&nbsp;&nbsp;<strong>21</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          Gender:&nbsp;&nbsp;<strong>NA</strong>
                        </v-col>
                        <v-col cols="12">
                          Belonging to  any Indigenous Peoples  (IP)<br>Community /Indigenous Cultural Community ?
                          <strong>&nbsp;&nbsp;No</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Mother Tongue:&nbsp;&nbsp;<strong>Bisaya</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Contact Number:&nbsp;&nbsp;<strong>639123456789</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Adress:&nbsp;&nbsp;<strong>Cebu</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Zip Code:&nbsp;&nbsp;<strong>6000</strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Father's Name:&nbsp;&nbsp;<strong></strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Mother's Name:&nbsp;&nbsp;<strong></strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Guardian's Name:&nbsp;&nbsp;<strong></strong>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          Contact Number:&nbsp;&nbsp;<strong>639123456789</strong>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </template>
              </v-dialog>
            </td>
            <td>
              <v-btn x-small color="success" dark>Approve</v-btn>
              <v-btn x-small color="error" dark>Decline</v-btn>
            </td>
          </tr>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
export default {
  data: () => ({
    search: "",
    items: [
      {
        text: "Home",
        disabled: false,
        href: "/admin",
      },
      {
        text: "Enrollment",
        disabled: true,
        href: "admin/enrollment",
      },
    ],

    headers: [
      {
        text: "Grade Level",
        align: "start",
        sortable: false,
        value: "grade_level",
      },
      { text: "Student Name", value: "student" },
      { text: "Details", value: "details" },
      { text: "Action", value: "action" },
    ],
    students: [
      {
        grade_level: "7",
        student: "Danica Caballero",
      },
      {
        grade_level: "8",
        student: "Chilla Jean Cabungcag",
      },
      {
        grade_level: "9",
        student: "Jericho James Villahermosa",
      },
      {
        grade_level: "10",
        student: "Christopher Alonzo",
      },
    ],
    grade_level: [7, 8, 9, 10, 11, 12],
  }),
};
</script>

<style>
.view_dtls_btn {
  font-family: Roboto;
  font-style: normal;
  font-weight: normal;

  color: #48d3ff;
}
</style>