<template>
  <div>
    <div>
      <v-toolbar color="#E5E5E5">
        <v-toolbar-title><h4>All Sections</h4></v-toolbar-title>

        <v-spacer></v-spacer>
        <v-icon>mdi-home</v-icon>
        <v-breadcrumbs :items="items"> </v-breadcrumbs>
      </v-toolbar>
    </div>
    <br /><br />
    <div>
      <v-row>
        <v-col cols="12" sm="6" md="8">
          <v-data-table
            :headers="headers"
            :items="teachers"
            :search="search"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item="row">
              <tr>
                <td>{{ row.item.name }}</td>
                <td>{{ row.item.email }}</td>
                <td>{{ row.item.phone }}</td>
                <td>
                  <v-icon class="btn">mdi-pencil</v-icon>
                  <v-icon class="btn">mdi-delete</v-icon>
                </td>
              </tr>
            </template>
          </v-data-table>
        </v-col>
        <v-col cols="6" md="4">
          <v-data-table
            :headers="headers"
            :items="teachers"
            :search="search"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item="row">
              <tr>
                <td>{{ row.item.name }}</td>
                <td>{{ row.item.email }}</td>
                <td>{{ row.item.phone }}</td>
                <td>
                  <v-icon class="btn">mdi-pencil</v-icon>
                  <v-icon class="btn">mdi-delete</v-icon>
                </td>
              </tr>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
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
        text: "Sections",
        disabled: true,
        href: "admin/all_sections",
      },
    ],
    junior_high: [
      {
        text: "Name",
        align: "start",
        sortable: false,
        value: "name",
      },
      { text: "Email", value: "email" },
      { text: "Phone Number", value: "phone" },
      { text: "Action", value: "action" },
    ],
    teachers: [
      {
        name: "Aileen Becher",
        email: "becher@mnhs.edu.ph",
        phone: "639123456789",
      },
    ],
  }),
};
</script>

<style>
</style>