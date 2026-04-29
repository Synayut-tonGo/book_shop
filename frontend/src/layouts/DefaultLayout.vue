<template>
  <div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Header -->
    <AppHeader />

    <!-- Main content -->
    <main class="flex-grow container mx-auto px-4 py-8">
      <router-view />
    </main>

    <!-- Footer -->
    <AppFooter />
  </div>
</template>

<script setup lang="ts">
import AppFooter from '@/components/layouts/AppFooter.vue';
import AppHeader from '@/components/layouts/AppHeader.vue';


</script>