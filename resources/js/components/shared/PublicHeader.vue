<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3'
import { login, logout, register } from '@/routes'
import { ref, computed } from 'vue'

const props = defineProps({
  canRegister: {
    type: Boolean,
    default: true,
  },
})

const page = usePage()

const showSearch = computed(() => page.component !== 'Welcome')

const searchQuery = ref('')

const submitSearch = () => {
  const component = page.component 

  const base = component.split('/')[0].toLowerCase()

  const validRoutes = ['articles', 'bookmarks']

  const targetRoute = validRoutes.includes(base) ? `/${base}` : '/articles'

  router.get(targetRoute, { search: searchQuery.value })
}
</script>

<template>
  <header
    class="w-full border-b border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center bg-white dark:bg-slate-900"
  >
    <h1 class="text-2xl font-bold">
      <Link href="/">News Portal</Link>
    </h1>

    <form
      v-if="showSearch"
      @submit.prevent="submitSearch"
      class="w-full max-w-md mx-6"
    >
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search articles..."
        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-slate-800 focus:outline-none focus:ring focus:ring-blue-200"
      />
    </form>

    <nav class="flex items-center gap-4">
      <Link
        v-if="$page.props.auth.user"
        :href="logout()"
        class="rounded-sm border px-4 py-1 text-sm hover:border-gray-400 dark:hover:border-gray-600"
      >
        Logout
      </Link>

      <template v-else>
        <Link
          :href="login()"
          class="rounded-sm px-4 py-1 text-sm hover:text-blue-600"
        >
          Log in
        </Link>

        <Link
          v-if="props.canRegister"
          :href="register()"
          class="rounded-sm border px-4 py-1 text-sm hover:border-gray-400 dark:hover:border-gray-600"
        >
          Register
        </Link>
      </template>
    </nav>
  </header>
</template>
