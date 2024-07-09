<template>
    <div class="form-container">
        <ProgressSpinner v-show="blocked" class="progress" />
        <BlockUI :blocked="blocked">
            <div class="form flex flex-col items-center ">
                <div class="form-title flex flex-col items-center">
                    <h2 class="text-xl">Return application Form</h2>
                    <span
                        v-show="formError.active"
                        class="form-error text-sm text-red-600"
                        v-for="(line,lineNumber) of formError.message.split('\n')"
                        v-bind:key="lineNumber"
                    >{{line}}</span>
                </div>
                <div class="form-inputs flex flex-col items-center">
                    <FloatLabel>
                        <InputText
                            id="form-firstname"
                            type="text"
                            :invalid="formData.first_name.invalid"
                            v-model="formData.first_name.value" />
                        <label for="form-firstname">First Name</label>
                    </FloatLabel>
                    <FloatLabel>
                        <InputText
                            id="form-lastname"
                            type="text"
                            :invalid="formData.last_name.invalid"
                            v-model="formData.last_name.value" />
                        <label for="form-lastname">Last Name</label>
                    </FloatLabel>
                    <FloatLabel>
                        <InputText
                            id="form-phone"
                            type="text"
                            :invalid="formData.phone.invalid"
                            v-model="formData.phone.value" />
                        <label for="form-phone">Phone Number</label>
                    </FloatLabel>
                    <FloatLabel>
                <Textarea
                    id="form-text"
                    :invalid="formData.text.invalid"
                    v-model="formData.text.value"
                    autoResize
                    rows="5"/>
                        <label>Application text</label>
                    </FloatLabel>
                </div>
                <div class="form-actions">
                    <Button
                        @click="sendForm">Send application</Button>
                </div>
            </div>
        </BlockUI>
    </div>


</template>
<script setup>
    import FloatLabel from 'primevue/floatlabel';
    import InputText from 'primevue/inputtext';
    import Textarea from 'primevue/textarea';
    import Button from 'primevue/button';
    import {reactive, ref} from 'vue';
    import axios from "axios";
    import {route} from "ziggy-js"
    import BlockUI from 'primevue/blockui';
    import ProgressSpinner from 'primevue/progressspinner';


    const blocked = ref(false);
    const emit = defineEmits(['success'])

    function sendForm(){
        blocked.value = true;
        const isFormInvalid = validateForm();
        if (isFormInvalid){
            blocked.value = false;
            return;
        }
        axios.post(route('api.return-application.store'), {
            first_name: formData.first_name.value,
            last_name: formData.last_name.value,
            phone: formData.phone.value,
            text: formData.text.value
        })
            .then((response) => {
                formError.active = false;
                emit('success');
            })
            .catch((error) => {
                console.log(error)
                blocked.value = false;
                if (!("response") in error){
                    formError.active = true;
                    formError.message = 'Something went wrong'
                    return;
                }
                if (error.response.status !== 422){
                    formError.active = true;
                    formError.message = 'Something went wrong'
                    return;
                }
                formError.active = true;
                formError.message = ''
                for (let [inputKey, messages] of Object.entries(error.response.data.errors)){
                    formData[inputKey].invalid = true;
                    messages.forEach(message => {
                        formError.message = formError.message + '\n' + message;
                    })
                }
            })
    }
    function validateForm(){
        let anyInputInvalid = false;
        for (let [key, input] of Object.entries(formData)){
            input.invalid = input.value.length === 0;
            if (key === 'phone'){
                input.invalid = input.value.length < 9 || input.value.length > 11
            }
            if (input.invalid === true){
                anyInputInvalid = true;
            }
        }
        return anyInputInvalid;
    }
    const formError = reactive({
        active: false,
        message: ''
    })
    const formData = reactive({
        first_name: {
            value: '',
            invalid: false
        },
        last_name: {
            value: '',
            invalid: false
        },
        phone: {
            value: '',
            invalid: false
        },
        text: {
            value: '',
            invalid: false
        },
    })
</script>

<style scoped>
.progress{
    position: fixed;
    width: 80px;
    height: 80px;
    left: calc(50vw - 40px);
    top: calc(50vh - 40px);
    z-index: 999;
}
.form{
    max-width: fit-content;
    padding: 10px 25px;
    border: 1px solid black;
    border-radius: 15px;
    background-color: rgba(8, 47, 73, 0.1);
}
.form > *{
    margin: 5px 0;
}
input, textarea{
    width: 250px;
}
.form-inputs > *{
    margin-top: 20px;
}
</style>
