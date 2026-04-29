<script setup lang="ts">
import AppSidebar from '@/components/layouts/AppSidebar.vue'
</script>

<template>
  <div class="flex w-full h-screen bg-warm-sand">
    <AppSidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <header class="w-full h-16 bg-white flex items-center px-4 shadow-md">
        <h1 class="text-xl font-semibold text-moss-green">Dashboard</h1>
      </header>

      <main class="flex-1 p-7 overflow-y-auto">
        <div class="bg-white w-full h-full rounded-md shadow-md">
          <RouterView />
        </div>
      </main>
    </div>
  </div>
</template>