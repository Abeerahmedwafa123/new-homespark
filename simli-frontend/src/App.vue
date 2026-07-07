<script setup>
import SimliAvatar from "./components/SimliAvatar.vue";
</script>

<template>
  <!--
    In standalone dev, VITE_API_BASE_URL (from .env) points at the Laravel API.
    When merged into the LMS, pass the LMS's own base URL, e.g.
    <SimliAvatar :api-base-url="lmsApiBase" />
  -->
  <SimliAvatar />
</template>
