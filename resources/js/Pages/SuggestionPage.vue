<template>
    <div class="flex gap-8">
        <section> <AdminsideBar /> </section>
        <main class="flex-1 overflow-y-auto p-8">
    <div class="mx-auto max-w-3xl">

      <!-- PageHeading -->
      <header class="flex flex-wrap items-center justify-between gap-4 border-b border-border-light pb-6 dark:border-border-dark">
        <div class="flex flex-col gap-1">
          <h1 class="text-4xl font-black tracking-tighter text-text-primary-light dark:text-text-primary-dark">Suggestion Box</h1>
          <p class="text-base font-normal text-text-secondary-light dark:text-text-secondary-dark">Share your ideas to improve the club.</p>
        </div>

        <button
          class="flex h-10 cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg bg-blue-500 px-5 text-sm font-bold text-white transition-opacity hover:opacity-90"
          @click="showNew = true"
        >
          <i class="fas fa-plus"></i>
          <span class="truncate">New Suggestion</span>
        </button>
      </header>

      <!-- Suggestions Feed -->
      <div class="mt-8 flex flex-col gap-8">

        <!-- Empty state -->
        <div v-if="!suggestions || suggestions.length === 0" class="text-center text-gray-500 py-12">
          Aucune suggestion pour le moment. Sois le premier à proposer une idée !
        </div>

        <!-- Suggestion Card -->
        <article
          v-for="suggestion in suggestions"
          :key="suggestion.id"
          class="flex flex-col rounded-lg border border-border-light bg-card-light dark:border-border-dark dark:bg-card-dark"
        >
          <!-- Card Content -->
          <div class="p-6">
            <div class="flex items-center gap-3">
              <div
                class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                :style="`background-image: url('${suggestion.user.avatar ?? defaultAvatar}')`"
                :title="suggestion.user.pseudo || suggestion.user.name || 'Member'"
              ></div>

              <div class="flex flex-col">
                <p class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark">
                  {{ suggestion.user.pseudo || suggestion.user.name || 'Unknown' }}
                </p>
                <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">
                  {{ timeAgo(suggestion.created_at) }}
                </p>
              </div>

              <div class="ml-auto flex items-center gap-2">
                <!-- Only show delete for admin -->
                <button
                  v-if="isAdmin"
                  class="text-text-secondary-light dark:text-text-secondary-dark"
                  @click="confirmDelete(suggestion.id)"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>

                <button class="text-text-secondary-light dark:text-text-secondary-dark" @click="toggleExpand(suggestion.id)">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
              </div>
            </div>

            <div class="mt-4">
              <h2 class="text-xl font-bold leading-tight text-text-primary-light dark:text-text-primary-dark">{{ suggestion.title }}</h2>
              <p class="mt-2 text-base font-normal text-text-secondary-light dark:text-text-secondary-dark">{{ suggestion.content }}</p>
            </div>
          </div>

          <!-- Reaction Bar -->
          <div class="flex items-center gap-4 border-t border-border-light px-6 py-2 dark:border-border-dark">
            <button
              @click="react(suggestion.id, 'like')"
              :class="['flex items-center gap-2 rounded-lg p-2 transition-colors', userReacted(suggestion, 'like') ? 'bg-background-light dark:bg-background-dark' : 'hover:bg-background-light dark:hover:bg-background-dark']"
            >
              <i class="fas fa-thumbs-up"></i>
              <span class="text-sm font-bold text-positive">{{ countReactions(suggestion, 'like') }}</span>
            </button>

            <button
              @click="react(suggestion.id, 'dislike')"
              :class="['flex items-center gap-2 rounded-lg p-2 transition-colors', userReacted(suggestion, 'dislike') ? 'bg-background-light dark:bg-background-dark' : 'hover:bg-background-light dark:hover:bg-background-dark']"
            >
              <i class="fas fa-thumbs-down"></i>
              <span class="text-sm font-bold text-negatif">{{ countReactions(suggestion, 'dislike') }}</span>
            </button>

            <button
              @click="toggleComments(suggestion.id)"
              class="flex items-center gap-2 rounded-lg p-2 text-text-secondary-light transition-colors hover:bg-background-light dark:text-text-secondary-dark dark:hover:bg-background-dark"
            >
              <i class="fas fa-comment-dots"></i>
              <span class="text-sm font-bold">{{ suggestion.comments?.length || 0 }}</span>
            </button>
          </div>

          <!-- Comments Section (toggle) -->
          <div v-if="expanded[suggestion.id]" class="border-t border-border-light bg-background-light p-6 dark:border-border-dark dark:bg-background-dark">
            <!-- Existing comments -->
            <div v-if="suggestion.comments && suggestion.comments.length">
              <div v-for="c in suggestion.comments" :key="c.id" class="flex w-full flex-row items-start justify-start gap-3 mb-4">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-10 shrink-0" :style="`background-image: url('${c.user.avatar ?? defaultAvatar}')`"></div>
                <div class="flex h-full flex-1 flex-col items-start justify-start gap-1">
                  <div class="flex items-baseline gap-x-2">
                    <p class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark">{{ c.user.pseudo || c.user.name }}</p>
                    <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">{{ timeAgo(c.created_at) }}</p>
                  </div>
                  <p class="text-sm text-text-primary-light dark:text-text-primary-dark">{{ c.content }}</p>
                </div>
              </div>
            </div>

            <!-- Add comment form -->
            <form @submit.prevent="submitComment(suggestion.id)" class="mt-2">
              <div class="flex gap-2">
                <input
                  v-model="commentInputs[suggestion.id]"
                  type="text"
                  placeholder="Write a comment..."
                  class="flex-1 rounded-lg border px-3 py-2 text-sm bg-white dark:bg-card-dark"
                />
                <button class="px-3 py-2 rounded-lg bg-primary text-white" :disabled="!commentInputs[suggestion.id]">Comment</button>
              </div>
            </form>
          </div>
        </article>
      </div>
    </div>

    <!-- New Suggestion Modal -->
    <div v-if="showNew" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
      <div class="w-full max-w-2xl rounded-lg bg-white dark:bg-card-dark p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold">New Suggestion</h3>
          <button @click="closeNew" class="text-text-secondary-light dark:text-text-secondary-dark">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <form @submit.prevent="submitSuggestion" class="space-y-4">
          <input
            v-model="newSuggestion.title"
            type="text"
            placeholder="Title"
            class="w-full rounded-lg border px-3 py-2"
            required
          />
          <textarea
            v-model="newSuggestion.content"
            placeholder="Describe your idea..."
            class="w-full rounded-lg border px-3 py-2"
            rows="6"
            required
          ></textarea>

          <div class="flex justify-end gap-3">
            <button type="button" @click="closeNew" class="px-4 py-2 rounded bg-gray-200">Cancel</button>
            <button :disabled="creating" class="px-4 py-2 rounded bg-blue-500 text-white">
              {{ creating ? 'Posting...' : 'Post Suggestion' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

    </div>

  
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminsideBar from "@/Components/AdminsideBar.vue";

// Props from controller (ensure controller sends these)
const props = defineProps({
  suggestions: { type: Array, default: () => [] },
  authUser: { type: Object, default: () => null },
})

// local UI state
const showNew = ref(false)
const creating = ref(false)
const newSuggestion = reactive({ title: '', content: '' })

const expanded = reactive({})         // track expanded comments per suggestion id
const commentInputs = reactive({})    // store text per suggestion id

const defaultAvatar = '/images/default-avatar.png' // fallback

// helpers
const suggestions = computed(() => props.suggestions || [])
const isAdmin = computed(() => {
  if (!props.authUser) return false
  // adapt to your app: either is_admin boolean or role field
  return props.authUser.is_admin || props.authUser.role === 'admin' || props.authUser.role === 'administrateur'
})

// time ago simple formatter
function timeAgo(date) {
  if (!date) return ''
  const d = new Date(date)
  const diff = Math.floor((Date.now() - d.getTime()) / 1000)
  if (diff < 60) return `${diff}s ago`
  if (diff < 3600) return `${Math.floor(diff/60)}m ago`
  if (diff < 86400) return `${Math.floor(diff/3600)}h ago`
  return `${Math.floor(diff/86400)}d ago`
}

// UI toggles
function toggleExpand(id) {
  expanded[id] = !expanded[id]
  if (!commentInputs[id]) commentInputs[id] = ''
}
function toggleComments(id) {
  expanded[id] = !expanded[id]
  if (!commentInputs[id]) commentInputs[id] = ''
}

// CRUD actions
async function submitSuggestion() {
  if (!newSuggestion.title || !newSuggestion.content) return
  creating.value = true
  try {
    await router.post('/suggestions', {
      title: newSuggestion.title,
      content: newSuggestion.content,
    })
    // after Inertia post, page will refresh; but to be safe reset
    newSuggestion.title = ''
    newSuggestion.content = ''
    showNew.value = false
  } finally {
    creating.value = false
  }
}

async function react(suggestionId, type) {
  try {
    await router.post(`/suggestions/${suggestionId}/react`, { type })
  } catch (e) {
    console.error(e)
  }
}

async function submitComment(suggestionId) {
  const content = (commentInputs[suggestionId] || '').trim()
  if (!content) return
  try {
    await router.post(`/suggestions/${suggestionId}/comment`, { content })
    commentInputs[suggestionId] = ''
  } catch (e) {
    console.error(e)
  }
}

function confirmDelete(suggestionId) {
  if (!isAdmin.value) return
  if (!confirm('Voulez-vous vraiment supprimer cette suggestion ?')) return
  router.delete(`/suggestions/${suggestionId}`)
}

// small helpers to compute reaction counts and whether current user reacted
function countReactions(suggestion, type) {
  if (!suggestion.reactions) return 0
  return suggestion.reactions.filter(r => r.type === type).length
}

function userReacted(suggestion, type) {
  if (!props.authUser) return false
  if (!suggestion.reactions) return false
  return suggestion.reactions.some(r => r.user_id === props.authUser.id && r.type === type)
}

// modal helpers
function closeNew() {
  showNew.value = false
  newSuggestion.title = ''
  newSuggestion.content = ''
}
</script>

<style scoped>
/* sizes used in markup */
.size-10 { width: 40px; height: 40px; }
.bg-card-light { background-color: white; }
.bg-card-dark { background-color: #0f172a; } /* adapt if you have dark vars */
.text-positive { color: #16a34a; } /* green */
.text-negatif { color: #a31616; } /* red */
</style>
