<template>
  <div class="container-xl py-3">
    <h1>BIT Webspace Vue Admin Frontend</h1>
    <p>Bootstrap is installed</p>
    <p>Current Count {{ counter }}
      <button class="btn btn-outline-primary" @click="doCount">Count</button>
    </p>
  </div>
</template>

<script lang="ts" setup>
import {ref} from 'vue';

const counter = ref(0);

function doCount() {
  counter.value++;
}
</script>
