<template>
    <div class="page-container">
        <application-form v-show="showForm" @success.once="showSuccess"></application-form>
        <div v-show="!showForm" class="form-success-card flex flex-col items-center">
            <h2 class="text-lg">Your application is confirmed, you can check it in the table</h2>
            <Link :href="route('return-applications-table.index')">
                <Button label="Applications table" severity="success" class="mt-3" icon="pi pi-search"></Button>
            </Link>
        </div>
    </div>

</template>

<script setup>
    import ApplicationForm from '@/js/Components/ApplicationForm.vue'
    import {ref} from "vue";
    import { Link } from '@inertiajs/vue3'
    import {route} from "ziggy-js"
    import Button from 'primevue/button';

    const showForm = ref(true);

    function showSuccess(){
        showForm.value = false;
    }
</script>

<style scoped>
    .page-container{
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
