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
          <v-card color="basil">
            <v-card-title class="text-center justify-center py-6">
              <h1 class="font-weight-bold display-3 basil--text">
                Junior High
              </h1>
            </v-card-title>

            <v-tabs
              v-model="tab"
              background-color="#C4C4C4"
              color="basil"
              fixed-tabs
            >
              <v-tab v-for="item in junior_high" :key="item.text">
                {{ item.text }}
              </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab">
              <v-tab-item v-for="item in junior_high" :key="item.content">
                <v-card-title>
                  {{ item.text }} Sections
                  <v-spacer></v-spacer>
                  <div class="add_btn">
                    <v-dialog v-model="dialog" persistent max-width="300px">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" dark v-bind="attrs" v-on="on"
                          ><v-icon>mdi-plus</v-icon>Add Section</v-btn
                        >
                      </template>
                      <v-card>
                        <v-card-title>
                          <span class="headline"
                            >Add {{ item.text }} Sections</span
                          >
                        </v-card-title>
                        <v-card-text>
                          <v-container>
                            <v-text-field
                              label="Section name"
                              required
                            ></v-text-field>
                            <v-text-field
                              label="Capacity"
                              type="number"
                            ></v-text-field>
                          </v-container>
                        </v-card-text>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn :disabled="loading" color="error darken-1" @click="dialog = false">
                            Cancel
                          </v-btn>
                          <v-btn :loading="loading" color="blue darken-1" @click="addSection">
                            Save
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                  </div>
                </v-card-title>
                <v-container>
                  <div v-for="i in item.content" :key="i.section_name">
                    <v-card max-width="250">
                      <v-card-title
                        ><v-icon>mdi-home-group</v-icon
                        >{{ i.section_name }}</v-card-title
                      >
                      <v-card-text>
                        <div>
                          <v-icon>mdi-home-account</v-icon> {{ i.capacity }}
                        </div>
                      </v-card-text>
                      <v-card-actions>
                        <v-progress-linear
                          :value="(i.students_enrolled / i.capacity) * 100"
                          height="25"
                        >
                          <strong
                            >{{ i.students_enrolled }} Students Enrolled</strong
                          >
                        </v-progress-linear>
                      </v-card-actions>
                    </v-card>
                  </div>
                </v-container>
              </v-tab-item>
            </v-tabs-items>
          </v-card>
        </v-col>
        <v-col cols="6" md="4"> </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    loading: false,
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
    tab: null,
    g_level: "",
    junior_high: [
      {
        text: "Grade 7",
        content: [
          {
            section_name: "Molave",
            capacity: 35,
            students_enrolled: 33,
          },
        ],
      },
      { text: "Grade 8" },
      { text: "Grade 9" },
      { text: "Grade 10" },
    ],
    senior_high: [
      {
        text: "Grade 11",
      },
      { text: "Grade 12" },
    ],
  }),

  methods: {
    async addSection() {
      this.loading = true;

      await new Promise((resolve) => setTimeout(resolve, 3000));

      this.loading = false;
      this.dialog = false
    },
  },
};
</script>

<style>
.mx-auto {
  width: 250px;
  height: 100px;

  border-radius: 5px;
}
</style>