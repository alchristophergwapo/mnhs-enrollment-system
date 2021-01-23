<template>
  <div>
    <div>
      <v-toolbar color="#E5E5E5">
        <v-toolbar-title><h4>All Students</h4></v-toolbar-title>

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
          label="Grade Level"
          hide-details
          dense
          outlined
        ></v-select>
        &nbsp;&nbsp;
        <v-select
          :items="section"
          menu-props="auto"
          label="Section"
          hide-details
          dense
          outlined
        ></v-select>
        <v-spacer></v-spacer>
        <span>Adviser: Aileen Becher</span>
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
            <td>{{ row.item.student }}</td>
            <td>{{ row.item.age }}</td>
            <td>{{ row.item.address }}</td>
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
        text: "Students",
        disabled: true,
        href: "admin/students",
      },
    ],
    headers: [
      {
        text: "Student Name",
        align: "start",
        sortable: false,
        value: "student",
      },
      { text: "Age", value: "age" },
      { text: "Address", value: "address" },
    ],
    students: [
      {
        student: "Danica Caballero",
        age: 21,
        address: "Moalboal",
      },
      {
        student: "Chilla Jean Cabungcag",
        age: 21,
        address: "Badian",
      },
      {
        student: "Jericho James Villahermosa",
        age: 21,
        address: "Bulac",
      },
      {
        student: "Christopher Alonzo",
        age: 21,
        address: "Salug",
      },
    ],
    grade_level: [7, 8, 9, 10, 11, 12],
    section: [
      "Section1",
      "Section2",
      "Section3",
      "Section4",
      "Section5",
      "Section6",
    ],
  }),
};
</script>

<style>
.gl_filter {
  font-family: Roboto;
  font-style: normal;
  font-weight: normal;
  font-size: 14px;
  line-height: 25px;
  /* or 179% */

  text-align: center;

  color: #646468;
}
</style>
