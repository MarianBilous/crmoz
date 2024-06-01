<template>
    <div class="form-container">
        <form @submit.prevent="submitForm">
            <h1>Create Deal and Account</h1>

            <div class="field">
                <label class="label" for="account_name">Account Name:</label>
                <div class="control">
                    <input class="input" id="account_name" v-model="form.account_name" type="text">
                </div>
            </div>
            <div class="field">
                <label class="label" for="account_website">Account Website:</label>
                <div class="control">
                    <input class="input" id="account_website" v-model="form.account_website" type="text">
                </div>
            </div>
            <div class="field">
                <label class="label" for="account_phone">Account Phone:</label>
                <div class="control">
                    <input class="input" id="account_phone" v-model="form.account_phone" type="text">
                </div>
            </div>
            <hr>
            <div class="field">
                <label class="label" for="deal_name">Deal Name:</label>
                <div class="control">
                    <input class="input" id="deal_name" v-model="form.deal_name" type="text">
                </div>
            </div>
            <div class="field">
                <label class="label" for="deal_stage">Deal Stage:</label>
                <div class="control">
                    <input class="input" id="deal_stage" v-model="form.deal_stage" type="text">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link">Submit</button>
                </div>
            </div>
        </form>
        <div v-if="message" :class="{'notification is-success': success, 'notification is-danger': !success}">
            <button class="delete" @click="closeNotification"></button>
            <span v-html="message"></span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CreateDealAndAccount',
    data() {
        return {
            form: {
                account_name: '',
                account_website: '',
                account_phone: '',
                deal_name: '',
                deal_stage: '',
            },
            message: '',
            success: false,
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await axios.post('/api/zoho-create-account-and-deal', this.form);

                this.message = response.data.message;
                this.success = true;
            } catch (error) {
                if (error.response.data.errors) {
                    for (let field in error.response.data.errors) {
                        if (error.response.data.errors.hasOwnProperty(field)) {
                            this.message += error.response.data.errors[field][0] + '<br>';
                        }
                    }
                } else {
                    this.message = error.response.data.error + '<br>';
                }

                this.success = false;
            }
        },
        closeNotification() {
            this.message = '';
        }
    }
};
</script>

<style>
.form-container {
    display: ruby-text;
    width: 100%;
}
</style>
