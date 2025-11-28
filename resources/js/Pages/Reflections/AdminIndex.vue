<template>
    <AuthenticatedLayout>
        <Layout>
            <div class="max-w-full  mx-auto py-10 px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-[2rem] max-h-[95vh] ">

                    <div class="max-w-2xs max-h-[95vh] mx-auto md:mx-0 flex flex-col">
                        <CreateForm/>
                        <h1 class="mt-6">Mes reflexions</h1>
                        <div v-for="reflection in reflections.filter(reflection => reflection.user_id === $page.props.auth.user?.id)" class="scroller mt-6 max-h-90 overflow-y-auto" :key="reflection.id">
                            <UserReflection :reflection="reflection" />
                        </div>
                        
                    </div>
                    <div class="scroller overflow-y-scroll">

                        <div class=" scroller space-y-6 max-h-[900px] overflow-y-auto">
                            <div v-if="reflections.length === 0"
                                class="text-center p-8 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-gray-500 italic text-lg">
                                    Aucune réflexion active n'a été trouvée pour les derniers 7 jours.
                                </p>
                            </div>

                            <ReflectionCard 
                                v-for="reflection in reflections" 
                                :key="reflection.id"
                                :reflection="reflection" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import Layout from "@/Layouts/Layout.vue";
import UserReflection from "./UserReflection.vue";
import CreateForm from "./CreateForm.vue";
import ReflectionCard from "@/Components/ReflectionCard.vue";

// Récupération des props (ne pas destructurer directement defineProps)
const props = defineProps({
    reflections: {
        type: Array,
        default: () => []
    }
});

// exposer reflections pour le template (réactif)
const reflections = props.reflections;


</script>

<style scoped>
.scroller {
    scrollbar-width: none;
    scrollbar-color: #a0aec0 transparent;
}
</style>
