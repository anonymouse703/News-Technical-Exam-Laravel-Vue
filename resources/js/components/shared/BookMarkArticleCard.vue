<script setup lang="ts">
import noImage from '../../../assets/no-image.png'

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
    article: Article | null
    cardClass?: string
    imageHeight?: string
}>()

function getImage() {
    return props.article?.image_url?.trim() || noImage
}

function handleImageError(event: Event) {
    const img = event.target as HTMLImageElement
    img.src = noImage
}
</script>

<template>
    <div
        v-if="article"
        class="rounded-lg border border-gray-200 dark:border-gray-700 shadow-md overflow-hidden flex flex-col h-[450px] w-[300px]"
    >
        <!-- IMAGE -->
        <img
            :src="getImage()"
            @error="handleImageError"
            class="w-full h-40 object-cover"
        />

        <!-- CONTENT -->
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
        </div>
    </div>
</template>
