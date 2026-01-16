<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-semibold text-foreground">Dashboard</h1>
      <p class="mt-1 text-sm text-muted-foreground">Welcome to your dashboard!</p>
    </div>
    
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <!-- Dashboard stats or content can go here -->
      <div class="bg-card rounded-lg p-4 hover:bg-accent/50 transition-colors">
        <div class="flex items-center gap-4">
          <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground">Total Blogs</p>
            <p class="text-2xl font-semibold text-foreground">0</p>
          </div>
        </div>
      </div>

      <div class="bg-card rounded-lg p-4 hover:bg-accent/50 transition-colors">
        <div class="flex items-center gap-4">
          <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground">Active Users</p>
            <p class="text-2xl font-semibold text-foreground">0</p>
          </div>
        </div>
      </div>

      <div class="bg-card rounded-lg p-4 hover:bg-accent/50 transition-colors">
        <div class="flex items-center gap-4">
          <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground">Completed</p>
            <p class="text-2xl font-semibold text-foreground">0</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-card rounded-lg p-6">
      <h2 class="text-lg font-semibold text-foreground mb-4">Recent Activity</h2>
      <div class="space-y-3">
        <div class="flex items-center gap-3 text-sm">
          <div class="h-2 w-2 rounded-full bg-primary"></div>
          <span class="text-muted-foreground">No recent activity</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Dashboard home page content
</script>
