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
            <td><span class="btn view_dtls_btn" link>View Details</span></td>
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
    search: '',
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
    grade_level: [7, 8, 9, 10, 11, 12]
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