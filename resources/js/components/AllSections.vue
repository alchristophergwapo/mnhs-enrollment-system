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
        <v-col cols="12" md="7" lg="8">
          <v-card color="basil">
            <v-card-title class="text-center justify-center py-6">
              <h1 class="font-weight-bold display-2 basil--text">
                Junior High
              </h1>
            </v-card-title>

            <v-tabs
              v-model="tab1"
              background-color="#C4C4C4"
              color="basil"
              fixed-tabs
            >
              <v-tab v-for="item in junior_high" :key="item.text">
                {{ item.text }}
              </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab1">
              <v-tab-item v-for="(item, index) in junior_high" :key="index">
                <v-card-title>
                  {{ item.text }} Sections
                  <v-spacer></v-spacer>
                  <div class="add_btn">
                    <v-dialog v-model="dialog" persistent max-width="300px">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          color="primary"
                          small
                          dark
                          v-bind="attrs"
                          v-on="on"
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
                          <v-btn
                            :disabled="loading"
                            color="error darken-1"
                            @click="dialog = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            :loading="loading"
                            color="blue darken-1"
                            @click="addSection"
                          >
                            Save
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                  </div>
                </v-card-title>
                <v-container>
                  <v-row dense>
                    <v-col
                      v-for="(dta, index) in item.content"
                      :key="index"
                      cols="12"
                      lg="4"
                      md="6"
                      sm="6"
                    >
                      <v-card>
                        <v-card-title
                          ><v-icon>mdi-home-group</v-icon
                          >{{ dta.section_name }}</v-card-title
                        >
                        <v-card-text>
                          <div>
                            <v-icon>mdi-home-account</v-icon> {{ dta.capacity }}
                          </div>
                        </v-card-text>
                        <v-card-actions>
                          <v-progress-linear
                            :value="(dta.students_enrolled / dta.capacity) * 100"
                            height="25"
                          >
                            <strong
                              >{{ dta.students_enrolled }} Students
                              Enrolled</strong
                            >
                          </v-progress-linear>
                        </v-card-actions>
                      </v-card>
                    </v-col>
                    <v-card-title
                        class="text-center justify-center py-6"
                        
                      >
                        <h1 class="font-weight-bold display-1 basil--text" v-if="index">
                          No {{ item.text }} Sections created yet.
                        </h1>
                      </v-card-title>
                  </v-row>
                </v-container>
              </v-tab-item>
            </v-tabs-items>
          </v-card>
        </v-col>
        <v-col cols="6" lg="4" md="5">
          <v-card color="basil">
            <v-card-title class="text-center justify-center py-6">
              <h1 class="font-weight-bold display-2 basil--text">
                Senior High
              </h1>
            </v-card-title>

            <v-tabs
              v-model="tab2"
              background-color="#C4C4C4"
              color="basil"
              fixed-tabs
            >
              <v-tab v-for="(item, index) in senior_high" :key="index">
                {{ item.text }}
              </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab2">
              <v-tab-item v-for="(item, index) in senior_high" :key="index">
                <v-card-title>
                  {{ item.text }} Sections
                  <v-spacer></v-spacer>
                  <div class="add_btn">
                    <v-dialog v-model="dialog2" persistent max-width="300px">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          color="primary"
                          small
                          dark
                          v-bind="attrs"
                          v-on="on"
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
                          <v-btn
                            :disabled="loading"
                            color="error darken-1"
                            @click="dialog2 = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            :loading="loading"
                            color="blue darken-1"
                            @click="addSection"
                          >
                            Save
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                  </div>
                </v-card-title>
                <v-container>
                  <v-row dense>
                    <v-col
                      v-for="(i, index) in item.content"
                      :key="index"
                      cols="12"
                      lg="6"
                      md="12"
                      sm="12"
                    >
                      <v-card>
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
                              >{{ i.students_enrolled }} Students
                              Enrolled</strong
                            >
                          </v-progress-linear>
                        </v-card-actions>
                      </v-card>
                    </v-col>
                    <v-col>
                      <v-card-title class="text-center justify-center py-6" v-if="index">
                        <h1 class="font-weight-bold display-1 basil--text">
                          No {{ item.text }} Sections created yet.
                        </h1>
                      </v-card-title>
                    </v-col>
                  </v-row>
                </v-container>
              </v-tab-item>
            </v-tabs-items>
          </v-card>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    dialog2: false,
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
    tab1: null,
    tab2: null,
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
          {
            section_name: "Acacia",
            capacity: 35,
            students_enrolled: 20,
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
        content: [
          {
            section_name: "St. Augustine",
            capacity: 35,
            students_enrolled: 20,
          },
          {
            section_name: "St. Luke",
            capacity: 35,
            students_enrolled: 10,
          },
        ],
      },
      { text: "Grade 12" },
    ],
  }),

  methods: {
    async addSection() {
      this.loading = true;

      await new Promise((resolve) => setTimeout(resolve, 3000));

      this.loading = false;
      this.dialog = false;
    },
    filter(data) {
      console.log(data);
    }
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