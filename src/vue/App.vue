<script setup lang="ts">
import HelloVuetify from '@/components/HelloVuetify.vue';
</script>

<template>
  <v-app>
    <v-app-bar height="150">
      <v-img alt="Logo Fahrschule Langhof" src="@/assets/gfx/logo.svg" contain />
      <v-tabs align-tabs="end">
        <v-tab to="/">Start</v-tab>
        <v-tab to="/Unternehmen">Unternehmen</v-tab>
        <v-tab to="/Ausbildung">Ausbildung</v-tab>
        <v-tab to="/Anmeldung">Onlineanmeldung</v-tab>
        <v-tab to="/Kontakt">Kontakt</v-tab>
      </v-tabs>
    </v-app-bar>
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<style scoped lang="scss">

</style>
