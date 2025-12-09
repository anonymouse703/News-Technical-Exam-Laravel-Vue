<script setup lang="ts">
import { ref } from 'vue'
import { login } from '@/routes'
import { Bookmark } from 'lucide-vue-next'
import noImage from '../../../assets/no-image.png'
import { router, usePage, Link } from '@inertiajs/vue3'

type Article = {
    id: number
    title: string
    description: string
    author: string | null
    url: string
    image_url: string | null
    published_at: string
}

const props = defineProps<{
    article: Article
    cardClass?: string
    imageHeight?: string
}>()

const isBookmarked = ref(false)
const showLoginModal = ref(false)

const page = usePage()

const toggleBookmark = () => {
    if (!page.props.auth.user) {
        showLoginModal.value = true
        return
    }

    router.post(`/bookmark/${props.article.id}`,
        { article_id: props.article.id },
        {
            preserveScroll: true,
            onError: (error) => {
                console.error('Bookmark error:', error)
                isBookmarked.value = !isBookmarked.value
            },
            onSuccess: () => {
                isBookmarked.value = !isBookmarked.value
            }
        }
    )
}

function getImage() {
    return props.article.image_url?.trim() || noImage
}

function handleImageError(event: Event) {
    const img = event.target as HTMLImageElement
    img.src = noImage
}
</script>


<template>
    <div
        class="rounded-lg border border-gray-200 dark:border-gray-700 shadow-md overflow-hidden flex flex-col h-[450px] w-[300px]">

        <!-- <img :src="getImage()" @error="($event.target as HTMLImageElement).src = noImage"
            class="w-full h-40 object-cover" /> -->
        <img :src="getImage()" @error="handleImageError" class="w-full h-40 object-cover" />


        <div class="p-4 flex flex-col flex-1">
            <h3 class="font-bold text-md mb-2 line-clamp-2">
                <a :href="article.url" target="_blank" class="hover:text-blue-600">
                    {{ article.title }}
                </a>
            </h3>

            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2 line-clamp-3 flex-1">
                {{ article.description }}
            </p>

            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                <span>By {{ article.author ?? 'N/A' }}</span>
                <span>{{ new Date(article.published_at).toLocaleDateString() }}</span>
            </div>

            <button @click="toggleBookmark" :class="[
                'mt-auto flex items-center justify-center w-full gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200',
                isBookmarked
                    ? 'bg-gray-800 text-white dark:bg-gray-600 dark:text-white'
                    : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600'
            ]">
                <Bookmark :class="['w-5 h-5', isBookmarked ? 'text-white' : 'text-gray-600 dark:text-gray-200']" />
                <span>{{ isBookmarked ? 'Bookmarked' : 'Bookmark' }}</span>
            </button>

        </div>

        <!-- Login Modal -->
        <div v-if="showLoginModal" class="fixed inset-0 bg-gray-500/30 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full text-center">
                <h2 class="text-lg font-bold mb-4">Login Required</h2>
                <p class="mb-4">You need to be logged in to bookmark articles.</p>
                <div class="flex justify-center gap-2">
                    <button @click="showLoginModal = false"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <Link :href="login()"
                        class="px-4 py-2 rounded-sm text-sm bg-teal-900 text-white hover:text-gray-300">
                        Log in
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
