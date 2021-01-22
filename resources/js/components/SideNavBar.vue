<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant.sync="mini"
      color="#F0F9FC"
      permanent
      app
    >
      <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img src="images/logo.jpg"></v-img>
        </v-list-item-avatar>

        <v-list-item-title>Admin</v-list-item-title>

        <v-btn icon @click.stop="mini = !mini">
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
      </v-list-item>

      <v-divider></v-divider>
      <v-list>
        <v-list-item class="nav-link" link to="/admin">
          <img src="/images/icons/dashboard.png" alt="John" class="icon" />

          <v-list-item-content class="nav-def">
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="nav-link" link to="/admin/enrollment">
          <img src="/images/icons/enrollment.png" alt="John" class="icon" />

          <v-list-item-content class="nav-def">
            <v-list-item-title>Enrollment</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="nav-link" link to="/admin/all_students">
          <img src="/images/icons/all_students.png" alt="John" class="icon" />

          <v-list-item-content class="nav-def">
            <v-list-item-title>All Students</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="nav-link" link to="/admin/all_teachers">
          <img src="/images/icons/all_teachers.png" alt="John" class="icon" />

          <v-list-item-content class="nav-def">
            <v-list-item-title>All Teachers</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item class="nav-link" link to="/admin/all_sections">
          <img src="/images/icons/all_sections.png" alt="John" class="icon" />

          <v-list-item-content class="nav-def">
            <v-list-item-title>All Sections</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar dense app color="#3BBDDA">
      <!-- -->
      <v-toolbar-title>Welcome to Mantalongon, Dalaguete NHS</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon><v-icon>mdi-bell</v-icon></v-btn>
      <p>&nbsp;&nbsp;&nbsp;&nbsp;</p>
      <span class="label">Admin</span>
      <v-btn id="icon" icon>
        <v-icon>mdi-chevron-down</v-icon>
      </v-btn>
      <!-- <v-btn class="logout" icon> -->
      <span class="label btn">Logout</span>
      <!-- </v-btn> -->
    </v-app-bar>

    <!-- Sizes your content based upon application components -->
    <v-main>
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view></router-view>
      </v-container>
    </v-main>

    <v-footer app>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: true,
      mini: true,
    };
  },
};
</script>

<style>
.nav-link {
  margin-bottom: 15px;
  padding-bottom: 5px;
  padding-top: 5px;
}

.nav-link:hover {
  background: rgba(59, 189, 218, 0.5);
}

.nav-link:active {
  background-color: rgba(59, 189, 218);
}

.nav-def {
  position: absolute;
  left: 80px;
}

img.icon {
  height: 35px;
  width: auto;
}

.label {
  margin-right: 20px;
}
</style>