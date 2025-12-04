<template>
    <AuthenticatedLayout>
        <div class="flex ">
            <!-- Main content -->
            <main class="flex-1 p-6 md:p-10 bg-gray-50 dark:bg-gray-900 min-h-screen overflow-y-auto">
                <div class="mx-auto max-w-3xl">

                    <!-- Header -->
                    <header class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Suggestion Box
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                            Share your ideas to improve the club.
                            </p>
                        </div>
                        <button
                            class="flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all"
                            @click="showNew = true"
                        >
                            <i class="fas fa-plus text-sm"></i>
                            New Suggestion
                        </button>
                    </header>

                    <!-- MODAL SUCCESS -->
                    <Transition name="fade">
                        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
                            <!-- Fond sombre -->
                            <div class="absolute inset-0 bg-black/40"></div>

                            <!-- Modal -->
                            <div class="relative bg-white w-[90%] max-w-sm rounded-2xl shadow-xl p-6 text-center animate-bounce-in" >
                                <div class="w-14 h-14 mx-auto mb-3 flex items-center justify-center bg-green-100 text-green-600 rounded-full text-2xl">
                                    ✓
                                </div>
                                <p class="text-gray-800 font-semibold text-sm">
                                    {{ successMessage }}
                                </p>
                                <button
                                    class="mt-4 text-xs text-gray-500 hover:text-gray-700"
                                    @click="showModal = false"
                                    >
                                    Fermer
                                </button>
                            </div>
                        </div>

                    </Transition>


                    <!-- Suggestions -->
                    <div class="mt-10 flex flex-col gap-8 ">

                        <!-- Empty -->
                        <div
                            v-if="!suggestions || suggestions.length === 0"
                            class="text-center py-12 text-gray-500 dark:text-gray-400 text-lg border border-dashed rounded-xl bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 shadow-sm"
                        >
                            Aucune suggestion pour le moment. Sois le premier à proposer une idée !
                        </div>

                        <!-- Suggestion Card -->
                        <article
                            v-for="suggestion in suggestions"
                            :key="suggestion.id"
                            class="bg-gradient-to-r from-lime-400/20 to-emerald-500/20 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md hover:shadow-xl transition-all"
                        >
                            <div class="p-6">

                            <!-- User info -->
                            <div class="flex items-center gap-3">
                                <div
                                class="w-12 h-12 rounded-full bg-cover bg-center shadow"
                                :style="`background-image: url('${suggestion.user.avatar ?? defaultAvatar}')`"
                                ></div>

                                <div>
                                <p class="font-bold text-gray-800 dark:text-white">
                                    {{ suggestion.user.pseudo || suggestion.user.name || 'Unknown' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ timeAgo(suggestion.created_at) }}
                                </p>
                                </div>

                                <!-- Actions -->
                                <div class="ml-auto flex items-center gap-3 text-gray-500 dark:text-gray-400">
                                <button v-if="isAdmin" @click="deleteSuggestion(suggestion.id)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button @click="toggleExpand(suggestion.id)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="mt-5">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ suggestion.title }}
                                </h2>

                                <p class="text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                                {{ suggestion.content }}
                                </p>
                            </div>
                            </div>

                            <!-- Reaction bar -->
                            <div class="flex items-center gap-6 px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">

                                <!-- Like -->
                                <button
                                    @click="react(suggestion.id, 'like')"
                                    :class="[
                                    'flex items-center gap-2 px-3 py-2 rounded-lg transition',
                                    userReacted(suggestion, 'like')
                                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300'
                                        : 'hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
                                    ]"
                                >
                                    <i class="fas fa-thumbs-up"></i>
                                    <span>{{ countReactions(suggestion, 'like') }}</span>
                                </button>

                                <!-- Dislike -->
                                <button
                                    @click="react(suggestion.id, 'dislike')"
                                    :class="[
                                    'flex items-center gap-2 px-3 py-2 rounded-lg transition',
                                    userReacted(suggestion, 'dislike')
                                        ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                        : 'hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
                                    ]"
                                >
                                    <i class="fas fa-thumbs-down"></i>
                                    <span>{{ countReactions(suggestion, 'dislike') }}</span>
                                </button>

                                <!-- Comments -->
                                <button
                                    @click="toggleComments(suggestion.id)"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400 transition"
                                >
                                    <i class="fas fa-comment"></i>
                                    <span>{{ suggestion.comments?.length || 0 }}</span>
                                </button>

                            </div>

                            <!-- Comment Section -->
                            <div
                            v-if="expanded[suggestion.id]"
                            class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 space-y-5"
                            >

                            <!-- Existing comments -->
                            <div
                                v-for="c in suggestion.comments"
                                :key="c.id"
                                class="flex gap-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm hover:shadow-md transition duration-300"
                            >
                                <!-- Avatar -->
                                <div
                                class="w-12 h-12 rounded-full bg-cover bg-center ring-2 ring-gray-200 dark:ring-gray-700"
                                :style="`background-image: url('${c.user.avatar ?? defaultAvatar}')`"
                                ></div>

                                <!-- Content -->
                                <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                    {{ c.user.pseudo || c.user.name }}
                                    </p>
                                    <span class="text-xs text-gray-400">
                                    {{ timeAgo(c.created_at) }}
                                    </span>
                                </div>

                                <div
                                    class="mt-2 rounded-lg bg-gray-100 dark:bg-gray-700 px-4 py-3 text-gray-700 dark:text-gray-200 text-sm leading-relaxed"
                                >
                                    {{ c.content }}
                                </div>

                                <div
                                    v-if="c.user_id === $page.props.auth.user.id"
                                    class="flex gap-3 mt-3"
                                >
                                    <button
                                    @click="editComment(c)"
                                    class="flex items-center gap-1 text-blue-600 text-xs font-semibold hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition"
                                    >
                                    <i class="fas fa-edit"></i>
                                    Modifier
                                    </button>

                                    <button
                                    @click="deleteComment(c.id)"
                                    class="flex items-center gap-1 text-red-600 text-xs font-semibold hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition"
                                    >
                                    <i class="fas fa-trash-alt"></i>
                                    Supprimer
                                    </button>
                                </div>
                                </div>
                            </div>

                            <!-- Add comment -->
                            <form @submit.prevent="submitComment(suggestion.id)">
                                <div class="flex gap-2">
                                <input
                                    v-model="commentInputs[suggestion.id]"
                                    type="text"
                                    placeholder="Write a comment..."
                                    class="flex-1 rounded-lg border border-gray-300 dark:border-gray-700 px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white"
                                />

                                <button
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    :disabled="!commentInputs[suggestion.id]"
                                >
                                    Comment
                                </button>
                                </div>
                            </form>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- NEW SUGGESTION MODAL -->
                <div v-if="showNew" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 max-w-2xl w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        New Suggestion
                        </h3>
                        <button @click="closeNew" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitSuggestion" class="space-y-4">
                        <input
                        v-model="newSuggestion.title"
                        type="text"
                        placeholder="Title"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white"
                        required
                        />

                        <textarea
                        v-model="newSuggestion.content"
                        placeholder="Describe your idea..."
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white"
                        rows="6"
                        required
                        ></textarea>

                        <div class="flex justify-end gap-3">
                        <button type="button" @click="closeNew" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg">
                            Cancel
                        </button>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            {{ creating ? "Posting..." : "Post Suggestion" }}
                        </button>
                        </div>
                    </form>
                    </div>
                </div>

                <!-- EDIT COMMENT MODAL -->
                <div v-if="showEdit" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 z-50">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 max-w-xl w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        Modifier le commentaire
                        </h3>
                        <button @click="showEdit = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form @submit.prevent="updateComment" class="space-y-4">
                        <textarea
                        v-model="editingComment.content"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white"
                        rows="4"
                        required
                        ></textarea>

                        <div class="flex justify-end gap-3">
                        <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg">
                            Annuler
                        </button>

                        <button :disabled="updating" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            {{ updating ? 'Modification...' : 'Modifier' }}
                        </button>
                        </div>
                    </form>
                    </div>
                </div>
            </main>

            <!-- Delete confirmation modal -->
                <ConfirmModal
                    v-model="showDeleteModalComment"
                    title="Supprimer votre commmentire"
                    message="Êtes-vous sûr(e) de vouloir supprimer cette commentaire ? Cette action est irréversible."
                    confirm-text="Supprimer"
                    cancel-text="Annuler"
                    variant="danger"
                    @confirm="confirmDeleteComment"
                    />
                    <!-- Delete confirmation modal -->
                        <ConfirmModal
                            v-model="showDeleteModalSuggestion"
                            title="Supprimer la suggestion"
                            message="Êtes-vous sûr(e) de vouloir supprimer cette suggestion ? Cette action est irréversible."
                            confirm-text="Supprimer"
                            cancel-text="Annuler"
                            variant="danger"
                            @confirm="confirmDeleteSuggestion"
                        />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


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

// Modal edit comment
const showEdit = ref(false)
const editingComment = reactive({ id: null, content: '' })
const updating = ref(false)

//  Modifier un commentaire
function editComment(c) {
    editingComment.id = c.id
  editingComment.content = c.content
  showEdit.value = true
}
//Pour soumettre la modification du commentaire
async function updateComment() {
  if (!editingComment.content.trim()) return

  updating.value = true
  try {
    await router.put(`/comments/${editingComment.id}`, {
      content: editingComment.content
    })
    // fermer le modal après update
    showEdit.value = false
    editingComment.id = null
    editingComment.content = ''
  } catch (err) {
    console.error(err)
  } finally {
    updating.value = false
  }
}

//  Supprimer un commentaire
const deletingCommentId = ref(null)
const showDeleteModalComment = ref(false)

function deleteComment(commentId) {

    deletingCommentId.value = commentId
    showDeleteModalComment.value = true
}

function confirmDeleteComment() {
    if (!deletingCommentId.value) return
    router.delete(`/comments/${deletingCommentId.value}`, {
       onSuccess: () => {
         deletingTeamId.value = null
       }
    })

}

//Supprimer une suggestion
const deletingSuggestId = ref(null)
const showDeleteModalSuggestion = ref(false)

function deleteSuggestion(suggestionId) {
    deletingSuggestId.value = suggestionId
    showDeleteModalSuggestion.value = true
}

function confirmDeleteSuggestion() {
    if (!deletingSuggestId.value) return
    router.delete(`/suggestions/${deletingSuggestId.value}`, {
       onSuccess: () => {
         deletingSuggestId.value = null
       }
    })

}
const page = usePage()

// On récupère le message venant de Inertia
const successMessage = computed(() => page.props.flash.success)

// Contrôle d'affichage du modal
const showModal = ref(false)

// Dès qu’il y a un message → affiche le modal
watch(successMessage, (val) => {
    if (val) {
        showModal.value = true

        // Disparait après 5 secondes
        setTimeout(() => {
            showModal.value = false
        }, 2000)
    }
})

</script>

<style scoped>
/* sizes used in markup */
.size-10 { width: 40px; height: 40px; }
.bg-card-light { background-color: white; }
.bg-card-dark { background-color: #0f172a; } /* adapt if you have dark vars */
.text-positive { color: #16a34a; } /* green */
.text-negatif { color: #a31616; } /* red */
</style>
