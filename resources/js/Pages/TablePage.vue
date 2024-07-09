<template>
    <DataTable :value="tableData" tableStyle="min-width: 50rem">
        <Column field="id" header="ID"></Column>
        <Column header="Spreadsheet">
            <template #body="slotProps">
                <a
                    class="underline"
                    :href="slotProps.data.link">
                    {{slotProps.data.link}}
                </a>
            </template>
        </Column>
        <Column field="created_at" header="Date"></Column>
    </DataTable>
</template>

<script setup>
    import DataTable from 'primevue/datatable';
    import Column from 'primevue/column';
    import { computed } from 'vue'
    import { usePage } from '@inertiajs/vue3'

    const page = usePage()
    const tableData = computed(() => page.props.tableData)
</script>

<style scoped>

</style>
